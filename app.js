import React, { useState } from "react";

function App() {
  const [inputSequence, setInputSequence] = useState("");
  const [coefficient, setCoefficient] = useState(null);
  const [decodedSequence, setDecodedSequence] = useState([]);
  const [baseGap, setBaseGap] = useState(0);
  const [error, setError] = useState("");

  // Helper: Calculate bits needed for max gap/diff
  function calculateBaseGap(diffs) {
    if (diffs.length === 0) return 1;
    const maxDiff = Math.max(...diffs);
    return Math.ceil(Math.log2(maxDiff + 1));
  }

  // Encoder: takes sorted tiny numbers, returns coefficient + baseGap
  function encodeSequence(nums) {
    if (nums.length < 2) {
      setError("Please enter at least two numbers.");
      return;
    }
    // Validate all even or all odd
    const isEven = nums[0] % 2 === 0;
    for (let n of nums) {
      if ((n % 2 === 0) !== isEven) {
        setError("All numbers must be all even or all odd.");
        return;
      }
    }

    // Calculate diffs
    const diffs = [];
    for (let i = 1; i < nums.length; i++) {
      diffs.push(nums[i] - nums[i - 1]);
    }
    const gap = calculateBaseGap(diffs);
    setBaseGap(gap);

    // Compose BigInt coefficient:
    // coefficient = (firstNumber << (gap * (length-1))) + encodedDiffs
    let encodedDiffs = 0n;
    for (let d of diffs) {
      encodedDiffs = (encodedDiffs << BigInt(gap)) | BigInt(d);
    }
    const coef = (BigInt(nums[0]) << BigInt(gap * diffs.length)) | encodedDiffs;

    setCoefficient(coef.toString());
    setDecodedSequence([]);
    setError("");
  }

  // Decoder: from coefficient, length and baseGap get original sequence
  function decodeSequence() {
    if (!coefficient) {
      setError("Please encode a sequence first or enter coefficient.");
      return;
    }
    if (baseGap <= 0) {
      setError("Invalid baseGap stored.");
      return;
    }

    try {
      const coefBig = BigInt(coefficient);
      const gap = baseGap;
      // length inferred from input length, or stored from encode
      // Here, to decode properly, we need the length: we get from input length
      const numsLength = inputSequence
        .split(",")
        .map((x) => x.trim())
        .filter((x) => x !== "").length;
      if (numsLength < 2) {
        setError("Please provide the original sequence length (at least 2).");
        return;
      }

      const mask = (1n << BigInt(gap)) - 1n;
      let diffPart = coefBig;
      const diffs = [];

      for (let i = 0; i < numsLength - 1; i++) {
        diffs.unshift(Number(diffPart & mask));
        diffPart >>= BigInt(gap);
      }
      const firstNumber = Number(diffPart);

      const result = [firstNumber];
      for (let d of diffs) {
        result.push(result[result.length - 1] + d);
      }
      setDecodedSequence(result);
      setError("");
    } catch (e) {
      setError("Failed to decode sequence: " + e.message);
    }
  }

  // Parse input sequence string
  function parseInputSequence() {
    return inputSequence
      .split(",")
      .map((s) => parseInt(s.trim()))
      .filter((n) => !isNaN(n));
  }

  return (
    <div style={{ maxWidth: 600, margin: "2rem auto", fontFamily: "Arial, sans-serif" }}>
      <h1>Sequence Encoder & Decoder</h1>

      <div style={{ marginBottom: "1rem" }}>
        <label>
          Enter sorted tiny numbers (all even or all odd), comma separated:
          <br />
          <input
            style={{ width: "100%", padding: "0.5rem", fontSize: "1rem" }}
            type="text"
            value={inputSequence}
            onChange={(e) => setInputSequence(e.target.value)}
            placeholder="e.g. 1,5,7,11,13"
          />
        </label>
      </div>

      <button onClick={() => encodeSequence(parseInputSequence())} style={{ marginRight: "1rem", padding: "0.5rem 1rem" }}>
        Encode Sequence
      </button>
      <button onClick={decodeSequence} style={{ padding: "0.5rem 1rem" }}>
        Decode Sequence
      </button>

      {error && <p style={{ color: "red" }}>{error}</p>}

      {coefficient && (
        <div style={{ marginTop: "1rem" }}>
          <p><strong>Encoded Coefficient:</strong> {coefficient}</p>
          <p><strong>Base Gap (bits per diff):</strong> {baseGap}</p>
        </div>
      )}

      {decodedSequence.length > 0 && (
        <div style={{ marginTop: "1rem" }}>
          <p><strong>Decoded Sequence:</strong></p>
          <p>[{decodedSequence.join(", ")}]</p>
        </div>
      )}
    </div>
  );
}

export default App;
