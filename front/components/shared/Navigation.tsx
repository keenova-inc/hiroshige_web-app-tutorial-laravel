'use client';

// import { CircleCheckIcon, CircleHelpIcon, CircleIcon } from 'lucide-react';
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

type Props = {
  authUser?: User;
};

export function Navigation({ authUser }: Props) {
  //   const isMobile = useIsMobile();
  const viewport = false; // スマフォサイズの時はtrueにしないと描画崩れ

  console.log(authUser);

  return (
    <NavigationMenu viewport={viewport} className="bg-header">
      <NavigationMenuList className="flex w-full justify-between lg:w-[700px] md:w-[500px] min-w-100 p-1">
        <NavigationMenuItem>
          <NavigationMenuLink asChild className={(navigationMenuTriggerStyle(), 'bg-header')}>
            <Link href="/" className="text-xl font-bold">
              記事サイト
            </Link>
          </NavigationMenuLink>
        </NavigationMenuItem>

        {authUser ? (
          <NavigationMenuItem>
            <NavigationMenuTrigger className="bg-header">ユーザ名</NavigationMenuTrigger>
            <NavigationMenuContent>
              <ul className="grid w-[200px] gap-4">
                <li>
                  <NavigationMenuLink asChild>
                    <Link href="#">マイページ</Link>
                  </NavigationMenuLink>
                  <NavigationMenuLink asChild>
                    <Link href="#">ログアウト</Link>
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
