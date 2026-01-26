import { cookies } from 'next/headers';
import 'server-only';

// Laravelへのfetch共通化
type Props = {
  url: string;
  headers?: { [key: string]: string };
  options: {
    method: 'GET' | 'POST' | 'PUT' | 'DELETE';
    headers?: { [key: string]: string };
  };
  datas?: {
    [key: string]: any;
  };
  hasPrefix?: boolean;
};

export async function apiFetch({ url, options, datas, hasPrefix = true }: Props) {
  // TODO: Cookieの設定
  const cookieStore = await cookies();

  const headers = {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    Cookie: cookieStore.toString(),
  };

  // TODO: 全てのメソッドをこれで吸収できるか？
  const baseUrl = hasPrefix ? process.env.API_BASE_URL : process.env.BASE_URL;
  return await fetch(`${baseUrl}${url}`, {
    credentials: 'include',
    ...options,
    headers: {
      ...headers,
      ...options.headers,
    },
    cache: 'no-cache',
    body: JSON.stringify(datas),
  });
}
