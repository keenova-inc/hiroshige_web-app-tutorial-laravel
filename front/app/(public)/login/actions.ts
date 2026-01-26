'use server';

import { cookies } from 'next/headers';
import { type Cookie, parse } from 'set-cookie-parser';
import messages from '@/lang/ja/messages';
import { apiFetch } from '@/lib/api';
import type { LoginSchema } from './schemas';

async function setCookie(setCookies: string[]) {
  let xXsrfToken: string | undefined;
  const cookieStore = await cookies();
  const parsedSetCookies: Cookie[] = parse(setCookies);

  parsedSetCookies.forEach((co: Cookie) => {
    if (co.name === 'XSRF-TOKEN' || co.name.startsWith('laravel')) {
      cookieStore.set(co.name, co.value, {
        expires: co.expires ? new Date(co.expires) : undefined,
        maxAge: co.maxAge,
        path: co.path,
        httpOnly: co.httpOnly ?? false,
        secure: co.secure ?? false,
        sameSite: (co.sameSite?.toLowerCase() as 'lax' | 'strict' | 'none') || 'lax',
      });
      // Requestヘッダー用にdecode
      if (co.name === 'XSRF-TOKEN') {
        xXsrfToken = decodeURI(co.value);
      }
    }
  });

  return xXsrfToken;
}

export async function login(formData: LoginSchema) {
  const Origin = process.env.ORIGIN || 'http://localhost:3000';
  try {
    const res = await apiFetch({
      url: '/sanctum/csrf-cookie',
      options: {
        method: 'GET',
        headers: {
          Origin,
        },
      },

      hasPrefix: false,
    });

    if (res.status === 204) {
      const headerSetCookies = res.headers.getSetCookie();
      const decodedToken = await setCookie(headerSetCookies);

      if (!decodedToken) {
        throw new Error(messages.serverError);
      }

      const loginRes = await apiFetch({
        url: '/login',
        options: {
          method: 'POST',
          headers: {
            'X-XSRF-TOKEN': decodedToken,
            Origin,
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
