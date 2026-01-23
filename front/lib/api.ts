// import { cookies } from 'next/headers';
import 'server-only';

// import type { ReadonlyRequestCookies } from 'next/dist/server/web/spec-extension/adapters/request-cookies';

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
};

export async function apiFetch({ url, options, datas }: Props) {
  // TODO: Cookieの設定
  // const cookies: Promise<ReadonlyRequestCookies> = await cookies();
  const headers = {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    // Cookie: cookies.toString(),
  };

  // TODO: 全てのメソッドをこれで吸収できるか？
  return await fetch(`${process.env.API_BASE_URL}${url}`, {
    ...options,
    headers: {
      ...headers,
      ...options.headers,
    },
    body: JSON.stringify(datas),
  });
}
