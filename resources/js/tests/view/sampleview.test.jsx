import React from 'react';
import { render, screen } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import SampleView from '@chappy/testing/SampleView';

describe('Sample view test', () => {
  it('renders heading', () => {
    const mockUser = {
      id: 1,
      fname: 'Jane'
    }
    render(<SampleView user={mockUser}/>);
    expect(screen.getByText(/Hello, Jane/i)).toBeInTheDocument();
  });
});
