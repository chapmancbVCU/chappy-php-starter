

import { describe, it, expect } from "vitest";
import { render, screen } from "@testing-library/react";
import SampleComponent from "@chappy/testing/SampleComponent";

describe("Sample component", () => {
  it("renders correct heading", () => {
    render(<SampleComponent />);
    // using regex with the i flag allows simpler case-insensitive comparison
    expect(screen.getByRole("heading").textContent).toMatch(/My Sample Component/i);
  });
});
