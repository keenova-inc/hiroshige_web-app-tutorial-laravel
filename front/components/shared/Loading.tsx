'use client';

import { Spinner } from '../ui/spinner';

export function Loading() {
  return (
    <div className="fixed inset-0 z-999 flex items-center justify-center bg-black/50 ">
      <Spinner className="size-30 text-loading animate-spin" />
    </div>
  );
}
