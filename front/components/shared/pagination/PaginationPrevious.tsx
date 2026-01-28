import { ChevronLeftIcon } from 'lucide-react';
import { PaginationLink } from '@/components/ui/pagination';
import { cn } from '@/lib/utils';

export function PaginationPrevious({
  className,
  ...props
}: React.ComponentProps<typeof PaginationLink>) {
  return (
    <PaginationLink
      aria-label="Go to previous page"
      size="default"
      className={cn('gap-1 px-2.5 sm:pl-2.5', className)}
      {...props}
    >
      <ChevronLeftIcon />
      <span className="hidden sm:block">前</span>
    </PaginationLink>
  );
}
