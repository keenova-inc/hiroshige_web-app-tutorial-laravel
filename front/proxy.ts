import { type NextRequest, NextResponse } from 'next/server';
import type { User } from './features/users/types/user';
import { apiFetch } from './lib/api';

// TODO: 調整中
export default async function proxy(req: NextRequest) {
  // console.log('@@@@@@@ PROXY PROXY @@@@@@@ ' /*req.nextUrl */);
  if (req.method === 'POST') {
    return NextResponse.next();
  }
  let res: Response | undefined;

  try {
    // console.log('11111111111');
    res = await apiFetch({
      url: '/login-check',
      options: {
        method: 'GET',
      },
    });
  } catch (e) {
    // console.log('22222222222');
    console.error(e);
    throw new Error('通信できなかったようだ');
    // return;
  }

  // console.log('res.ok: ', res?.ok);
  // console.log(await res.json());

  const { pathname } = req.nextUrl;

  if (res?.ok) {
    if (pathname === '/login') {
      // console.log('LOGIN PATH LOGIN PATH LOGIN PATH ');
      return NextResponse.redirect(new URL('/auth/mypage', req.nextUrl));
    }
    const requestHeaders = new Headers();
    const authUser: User = await res.json();
    requestHeaders.set('authUser', encodeURIComponent(JSON.stringify(authUser)));
    const userDatas = { request: { headers: requestHeaders } };

    // TODO: 変に残ったりしない？1リクエスト分だけであることを確認
    return NextResponse.next(userDatas);
  } else {
    if (pathname === '/login') {
      return NextResponse.next();
    }
  }

  // console.log('44444444444');
  return NextResponse.redirect(new URL('/login', req.nextUrl.origin));
}

export const config = {
  matcher: ['/auth/:path*', '/login'],
};
