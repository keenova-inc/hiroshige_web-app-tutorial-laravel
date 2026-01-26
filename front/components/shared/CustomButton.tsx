'use client';
import { Button } from '../ui/button';

const variantClass = {
  ok: 'bg-ok hover:bg-ok-hover',
  back: 'bg-back hover:bg-back-hover',
};

type Props = {
  children: React.ReactNode;
  type?: 'button' | 'submit';
  variant?: keyof typeof variantClass;
};

export default function CustomButton({ children, type = 'submit', variant = 'ok' }: Props) {
  return (
    <Button type={type} className={variantClass[variant]}>
      {children}
    </Button>
  );
}
