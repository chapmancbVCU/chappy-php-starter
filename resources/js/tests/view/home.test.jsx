import React from 'react';
import { render, screen } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import Index from '@/pages/home/Index';

describe('Renders the welcome message with the correct user name', () => {
  it('renders heading', () => {
    const mockUser = {
      id: 1,
      fname: 'Jane'
    }
    render(<Index user={mockUser}/>);
    expect(screen.getByText(/Hello, Jane/i)).toBeInTheDocument();
    expect(screen.getByText('This view is powered by React + Vite.')).toBeInTheDocument();
  });
});
