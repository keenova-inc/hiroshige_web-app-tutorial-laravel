'use client';
import { cn } from '@/lib/utils';
import { Button } from '../ui/button';

const variantClass = {
  ok: 'bg-ok hover:bg-ok-hover',
  back: 'bg-back hover:bg-back-hover',
};

type Props = {
  children: React.ReactNode;
  type?: 'button' | 'submit';
  variant?: keyof typeof variantClass;
  className?: string;
};

export default function CustomButton({
  children,
  type = 'submit',
  variant = 'ok',
  className,
}: Props) {
  return (
    <Button type={type} className={cn(variantClass[variant], className)}>
      {children}
    </Button>
  );
}
