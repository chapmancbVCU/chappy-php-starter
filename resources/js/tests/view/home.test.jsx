import React from 'react';
import { render, screen } from '@testing-library/react';
import { test, expect } from 'vitest';
import Index from '@/pages/home/Index';

describe('HomeView', () => {
  const user = { fname: "foo"}
  it('renders heading', () => {
    const mockUser = {
      id: 1,
      fname: "Jane Doe"
    }
    render(<Index user={mockUser}/>);
    //expect(screen.getByText(/home/i)).toBeInTheDocument();
  });
});
