'use client';

import {
  type Column,
  type ColumnDef,
  flexRender,
  getCoreRowModel,
  getSortedRowModel,
  type SortingState,
  useReactTable,
} from '@tanstack/react-table';
import { ArrowDown, ArrowUp, ChevronsUpDown } from 'lucide-react';
import * as React from 'react';
import { z } from 'zod';
import { Button } from '@/components/ui/button';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import type { Article } from '@/features/articles/types/article';
import { dateTimeFormatter } from '@/lib/formatter';

type UseDataTableOptions<TData> = {
  data: Array<TData>;
  columns: Array<ColumnDef<TData, unknown>>;
  getRowId: (row: TData) => string;
  initialSorting?: SortingState;
};

export function useDataTable<TData>(options: UseDataTableOptions<TData>) {
  const { data, columns, getRowId, initialSorting = [] } = options;

  const [sorting, setSorting] = React.useState<SortingState>(initialSorting);

  const table = useReactTable({
    data,
    columns,
    getRowId,
    state: { sorting },
    onSortingChange: setSorting,
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
  });

  return {
    table,
    sorting,
    setSorting,
  };
}

type DataTableColumnHeaderProps<TData, TValue> = {
  column: Column<TData, TValue>;
  title: string;
};

export const DataTableColumnHeader = <TData, TValue>({
  column,
  title,
}: DataTableColumnHeaderProps<TData, TValue>) => {
  const canSort = column.getCanSort();
  const sorted = column.getIsSorted();

  if (!canSort) {
    return (
      <span className="flex h-8 items-center text-sm font-medium text-foreground">{title}</span>
    );
  }

  return (
    <Button
      variant="ghost"
      size="sm"
      className="flex h-8 items-center gap-2 px-0 text-sm font-medium text-foreground"
      onClick={() => column.toggleSorting(sorted === 'asc')}
    >
      <span>{title}</span>
      {sorted === 'desc' ? (
        <ArrowDown className="h-4 w-4" />
      ) : sorted === 'asc' ? (
        <ArrowUp className="h-4 w-4" />
      ) : (
        <ChevronsUpDown className="h-4 w-4 opacity-50" />
      )}
    </Button>
  );
};

const schema = z.object({
  id: z.number(),
  title: z.string(),
  like: z.number(),
  username: z.string(),
  created_at: z.string(),
  updated_at: z.string(),
});

export const columns: ColumnDef<z.infer<typeof schema>>[] = [
  {
    accessorKey: 'title',
    header: ({ column }) => <DataTableColumnHeader column={column} title="タイトル" />,
    cell: ({ row }) => <div className="font-bold">{row.getValue('title')}</div>,
    enableSorting: false,
    enableHiding: false,
    size: 200,
  },
  {
    accessorKey: 'username',
    header: ({ column }) => <DataTableColumnHeader column={column} title="投稿者" />,
    cell: ({ row }) => <div className="">{row.getValue('username')}</div>,

    enableSorting: false,
    enableHiding: false,
    size: 100,
  },
  {
    accessorKey: 'like',
    header: ({ column }) => <DataTableColumnHeader column={column} title="いいねの数" />,
    cell: ({ row }) => <>{Number(row.getValue('like')).toLocaleString()}</>,
    enableSorting: false,
    enableHiding: false,
    size: 70,
  },
  {
    accessorKey: 'created_at',
    header: ({ column }) => <DataTableColumnHeader column={column} title="投稿日時" />,
    cell: ({ row }) => {
      const createdAtDate = new Date(row.getValue('created_at'));
      const createdAt = dateTimeFormatter.format(createdAtDate);
      return <div>{createdAt}</div>;
    },
    enableSorting: false,
    enableHiding: false,
    size: 150,
  },

  {
    accessorKey: 'updated_at',
    header: ({ column }) => <DataTableColumnHeader column={column} title="更新日時" />,
    cell: ({ row }) => {
      const updatedAtDate = new Date(row.getValue('updated_at'));
      const updatedAt = dateTimeFormatter.format(updatedAtDate);
      return <div>{updatedAt}</div>;
    },
    enableSorting: false,
    enableHiding: false,
    size: 150,
  },
];

export const DataTable1 = ({ datas }: { datas: Article[] }) => {
  // @@@@@@@@@ メンテ用 @@@@@@@@@
  // export const DataTable1 = () => {
  // const datas = [
  //   {
  //     content: 'Eveniet ipsa aut esse ',
  //     created_at: '2026-01-27T06:26:19.000000Z',
  //     deleted_at: null,
  //     id: 13,
  //     like: 0,
  //     title: 'Dr.',
  //     updated_at: '2026-01-27T06:26:19.000000Z',
  //     user_id: 33,
  //     username: '工藤 淳',
  //   },
  //   {
  //     content: 'Eveniet ipsa aut esse ',
  //     created_at: '2026-01-27T06:26:19.000000Z',
  //     deleted_at: null,
  //     id: 2,
  //     like: 0,
  //     title: 'Dr.Dr.Dr.Dr.Dr.Dr.Dr.Dr.Dr.Dr.Dr.Dr.Dr.',
  //     updated_at: '2026-01-27T06:26:19.000000Z',
  //     user_id: 33,
  //     username: '工藤 淳',
  //   },
  // ];

  const validatedData = schema.array().parse(datas);

  // console.log('@@@@@@ validatedData @@@@@@@@');
  // console.log(validatedData);

  const { table } = useDataTable({
    data: validatedData,
    columns,
    getRowId: (row) => row.id.toString(),
  });

  return (
    <section className="">
      <div className="container">
        <div className="w-full">
          <div className="overflow-hidden rounded-md border">
            <Table>
              <TableHeader className="bg-table-header">
                {table.getHeaderGroups().map((headerGroup) => (
                  <TableRow key={headerGroup.id}>
                    {headerGroup.headers.map((header) => {
                      return (
                        <TableHead key={header.id} className="p-0">
                          <div className={`flex items-center justify-center`}>
                            {header.isPlaceholder
                              ? null
                              : flexRender(header.column.columnDef.header, header.getContext())}
                          </div>
                        </TableHead>
                      );
                    })}
                  </TableRow>
                ))}
              </TableHeader>
              <TableBody>
                {table.getRowModel().rows?.length ? (
                  table.getRowModel().rows.map((row) => (
                    <TableRow key={row.id} data-state={row.getIsSelected() && 'selected'}>
                      {row.getVisibleCells().map((cell) => (
                        <TableCell key={cell.id}>
                          <div className={`truncate w-[${cell.column.getSize()}px]`}>
                            {flexRender(cell.column.columnDef.cell, cell.getContext())}
                          </div>
                        </TableCell>
                      ))}
                    </TableRow>
                  ))
                ) : (
                  <TableRow>
                    <TableCell colSpan={columns.length} className="h-20 text-center">
                      まだ記事がありません。
                    </TableCell>
                  </TableRow>
                )}
              </TableBody>
            </Table>
          </div>
        </div>
      </div>
    </section>
  );
};
