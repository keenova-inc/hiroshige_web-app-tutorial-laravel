import { DataTable1 } from '@/app/(public)/articles/data-table1';
import Paginate from '@/components/shared/pagination/Paginate';
import { SkeletonTable } from '@/components/shared/Skeleton';
import type { Article } from '@/features/articles/types/article';
import { apiFetch } from '@/lib/api';
import { search } from './actions';

type Props = {
  searchParams: { [key: string]: string | string[] };
  //   searchParams: {  };
};

export default async function ArticleTable({ searchParams }: Props) {
  const res = await search();
  const resJson = await res.json();
  const datas: Article[] = resJson.articles.data;

  return (
    <div className="flex flex-col gap-6">
      <DataTable1 datas={datas} />
      <Paginate />
    </div>
  );
}
