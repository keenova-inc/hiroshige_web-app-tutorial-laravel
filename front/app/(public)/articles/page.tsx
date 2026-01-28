// 'use client';
'use server';
import Link from 'next/link';
import { Suspense } from 'react';
import CustomButton from '@/components/shared/CustomButton';
import DatePicker from '@/components/shared/DatePicker';
import { SkeletonTable } from '@/components/shared/Skeleton';
import TextInput from '@/components/shared/TextInput';
import Title from '@/components/shared/Title';
import fields from '@/lang/ja/fields';
import ArticleTable from './ArticleTable';
import { getUrl, search } from './actions';

type Props = {
  searchParams: Promise<{ [key: string]: string | string[] }>;
};

export default async function Articles({ searchParams }: Props) {
  const params = await searchParams;
  console.log(params);
  // TODO:
  // 【複数のパラメータの時】
  // ?tags=react,nextjs
  // ?tags[]=react&tags[]=nextjs
  // ?tags=react&tags=nextjs

  return (
    <>
      <Title>記事一覧</Title>
      <div className="flex flex-col self-start ml-30 gap-y-9">
        <div className="flex flex-col w-130 gap-6">
          <form className="contents" action={getUrl}>
            <TextInput
              id="searchWords"
              type="text"
              label={`${fields.searchWords}(${fields.articleTitle}、${fields.authorName})`}
              placeholder={`${fields.articleTitle}、${fields.authorName}`}
              className="w-75"
            />
            <div className="flex gap-2">
              <DatePicker
                id="createStart"
                label={`${fields.publishedAt}(${fields.startDate})`}
                buttonInitialText={`${fields.publishedAt}(${fields.startDate})`}
                fieldWidth={40}
              />
              <span className="self-end text-xl">~</span>
              <DatePicker
                id="createEnd"
                label={`${fields.publishedAt}(${fields.endDate})`}
                buttonInitialText={`${fields.publishedAt}(${fields.endDate})`}
                fieldWidth={40}
              />
            </div>
            <div className="flex gap-2">
              <DatePicker
                id="updateStart"
                label={`${fields.updatedAt}(${fields.startDate})`}
                buttonInitialText={`${fields.updatedAt}(${fields.startDate})`}
                fieldWidth={40}
              />
              <span className="self-end text-xl">~</span>
              <DatePicker
                id="updateEnd"
                label={`${fields.updatedAt}(${fields.endDate})`}
                buttonInitialText={`${fields.updatedAt}(${fields.endDate})`}
                fieldWidth={40}
              />
            </div>

            <div className="flex justify-between">
              <CustomButton type="submit" className="w-40 text-xl">
                検&emsp;索
              </CustomButton>
              <Link href="/auth/articles/create">
                <CustomButton type="button" className="w-40 text-xl">
                  記事を投稿
                </CustomButton>
              </Link>
            </div>
          </form>
        </div>
        <Suspense key={params} fallback={<SkeletonTable />}>
          <ArticleTable searchParams={params} />
        </Suspense>
      </div>
    </>
  );
}
