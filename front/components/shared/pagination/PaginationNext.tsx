import { ChevronRightIcon } from 'lucide-react';
import { PaginationLink } from '@/components/ui/pagination';
import { cn } from '@/lib/utils';

export function PaginationNext({
  className,
  ...props
}: React.ComponentProps<typeof PaginationLink>) {
  return (
    <PaginationLink
      aria-label="Go to next page"
      size="default"
      className={cn('gap-1 px-2.5 sm:pr-2.5', className)}
      {...props}
    >
      <span className="hidden sm:block">次</span>
      <ChevronRightIcon />
    </PaginationLink>
  );
}
