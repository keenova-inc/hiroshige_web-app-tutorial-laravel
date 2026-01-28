'use server';

import { headers } from 'next/headers';
import Link from 'next/link';
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
  navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';
import type { User } from '@/features/users/types/user';
import LogoutLink from './LogoutLink';

export async function Navigation() {
  //   const isMobile = useIsMobile();
  const viewport = false; // TODO: スマフォサイズの時はtrueにしないと描画崩れ

  const headerInfo = await headers();
  const authUserString = headerInfo.get('authUser');
  const authUser: User | undefined = authUserString
    ? JSON.parse(decodeURIComponent(authUserString))
    : undefined;

  return (
    <NavigationMenu viewport={viewport} className="bg-header">
      <NavigationMenuList className="flex w-full justify-between lg:w-[700px] md:w-[500px] min-w-100 p-1">
        <NavigationMenuItem>
          <NavigationMenuLink asChild className={(navigationMenuTriggerStyle(), 'bg-header')}>
            <Link href="/articles" className="text-xl font-bold">
              記事サイト
            </Link>
          </NavigationMenuLink>
        </NavigationMenuItem>

        {authUser ? (
          <NavigationMenuItem>
            <NavigationMenuTrigger className="bg-header">{authUser.name}</NavigationMenuTrigger>
            <NavigationMenuContent>
              <ul className="grid w-[200px] gap-4">
                <li>
                  <NavigationMenuLink asChild>
                    <Link href="/auth/mypage">マイページ</Link>
                  </NavigationMenuLink>
                  <NavigationMenuLink asChild>
                    <LogoutLink />
                  </NavigationMenuLink>
                </li>
              </ul>
            </NavigationMenuContent>
          </NavigationMenuItem>
        ) : (
          <NavigationMenuItem>
            <NavigationMenuLink asChild className={(navigationMenuTriggerStyle(), 'bg-header')}>
              <Link href="/login">ログイン</Link>
            </NavigationMenuLink>
          </NavigationMenuItem>
        )}
      </NavigationMenuList>
    </NavigationMenu>
  );
}
