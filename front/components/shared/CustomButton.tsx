'use client';

import { Button } from '../ui/button';

type Props = {
  children: React.ReactNode;
  type?: 'button' | 'submit';
  className?: string;
};

export function OkButton({ children, type = 'submit' }: Props) {
  return (
    <Button type={type} className="bg-ok hover:bg-ok-hover">
      {children}
    </Button>
  );
}

export function BackButton({ children, type = 'submit' }: Props) {
  return (
    <Button type={type} className="bg-back hover:bg-back-hover">
      {children}
    </Button>
  );
}
