import { cookies } from 'next/headers';
import { type Cookie, parse } from 'set-cookie-parser';
import 'server-only';
import { RequestCookie } from 'next/dist/compiled/@edge-runtime/cookies';
import type { ReadonlyRequestCookies } from 'next/dist/server/web/spec-extension/adapters/request-cookies';
import { type NextRequest, NextResponse } from 'next/server';

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

async function getXXsrfToken(cookieStore: ReadonlyRequestCookies) {
  // console.log('@@@@@@@@@@ cookieStore @@@@@@@@@@');
  // console.log(cookieStore.get('XSRF-TOKEN'));
  const xsrfTokenCookie = cookieStore.get('XSRF-TOKEN');
  if (!xsrfTokenCookie) {
    return;
  }

  return decodeURI(xsrfTokenCookie.value);
}

export async function apiFetch({ url, options, datas, hasPrefix = true }: Props) {
  // ブラウザのCookie取得
  const cookieStore = await cookies();
  // X-XSRF-TOKENヘッダのセット内容取得
  const xXsrfToken = await getXXsrfToken(cookieStore);

  const headers = {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    Cookie: cookieStore.toString(),
    Origin: process.env.ORIGIN || 'http://localhost:3000',
    ...(xXsrfToken !== undefined && { 'X-XSRF-TOKEN': xXsrfToken }),
  };

  // TODO: 全てのメソッドをこれで吸収できるか？
  const baseUrl = hasPrefix ? process.env.API_BASE_URL : process.env.BASE_URL;

  // console.log('66666666666666666');

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
