'use server';

import { cookies } from 'next/headers';
import { type Cookie, parse } from 'set-cookie-parser';
import messages from '@/lang/ja/messages';
import { apiFetch } from '@/lib/api';
import type { LoginSchema } from './schemas';

async function setCookie(setCookies: string[]) {
  // 認証用の2つのCookieを取得
  const parsedSetCookies = parse(setCookies);
  const xsrfTokenCookie = parsedSetCookies.find((pc) => pc.name === 'XSRF-TOKEN');
  const regex = /laravel*/;
  const sessionCookie = parsedSetCookies.find((pc) => regex.test(pc.name) === true);
  if (!xsrfTokenCookie || !sessionCookie) {
    return;
  }

  // Cookieをセット
  const cookieStore = await cookies();
  [xsrfTokenCookie, sessionCookie].forEach((co: Cookie) => {
    cookieStore.set(co.name, co.value, {
      expires: co.expires ? new Date(co.expires) : undefined,
      maxAge: co.maxAge,
      path: co.path,
      httpOnly: co.httpOnly ?? false,
      secure: co.secure ?? false,
      sameSite: (co.sameSite?.toLowerCase() as 'lax' | 'strict' | 'none') || 'lax',
    });
  });

  // X-XSRF-TOKENヘッダセット用のCSRFTokenを返却
  return decodeURI(xsrfTokenCookie.value);
}

export async function login(formData: LoginSchema) {
  try {
    const res = await apiFetch({
      url: '/sanctum/csrf-cookie',
      options: {
        method: 'GET',
      },
      hasPrefix: false,
    });

    if (res.status === 204) {
      const headerSetCookies = res.headers.getSetCookie();
      const decodedToken = await setCookie(headerSetCookies);
      // console.log('decodedToken: ', decodedToken);

      if (!decodedToken) {
        throw new Error(messages.serverError);
      }

      const loginRes = await apiFetch({
        url: '/login',
        options: {
          method: 'POST',
          headers: {
            'X-XSRF-TOKEN': decodedToken,
          },
        },
        hasPrefix: false,
        datas: formData,
      });

      const loginHeaderCookies = loginRes.headers.getSetCookie();
      const loginDecodedToken = await setCookie(loginHeaderCookies);

      if (!loginDecodedToken) {
        throw new Error(messages.serverError);
      }

      return {
        status: loginRes.status,
        response: await loginRes.json(),
      };
    } else {
      return {
        status: res.status,
        response: await res.json(),
      };
    }
  } catch (e) {
    console.error(e);
    return {
      status: 500,
      response: { message: messages.networkError },
    };
  }
}
