import { Skeleton } from '@/components/ui/skeleton';

export function SkeletonTable() {
  return (
    <div className="flex flex-col gap-2">
      {Array.from({ length: 5 }).map((_, index) => (
        <div className="flex gap-4" key={index}>
          <Skeleton className="h-6 w-50" />
          <Skeleton className="h-6 w-25" />
          <Skeleton className="h-6 w-17" />
          <Skeleton className="h-6 w-37" />
          <Skeleton className="h-6 w-37" />
        </div>
      ))}
    </div>
  );
}
