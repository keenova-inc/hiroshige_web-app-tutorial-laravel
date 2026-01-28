'use client';

import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import CustomButton from '@/components/shared/CustomButton';
import TextInput from '@/components/shared/TextInput';
import { Card, CardContent } from '@/components/ui/card';
import { Field, FieldDescription, FieldGroup } from '@/components/ui/field';
import { Separator } from '@/components/ui/separator';
import { useLoading } from '@/contexts/LoadingContext';
import fields from '@/lang/ja/fields';
import messages from '@/lang/ja/messages';
import { useValidForm } from '@/lib/useValidForm';
import { cn } from '@/lib/utils';
import { login } from '../actions';
import { type LoginSchema, loginSchema } from '../schemas';

export function LoginForm({ className, ...props }: React.ComponentProps<'div'>) {
  const router = useRouter();
  const { setLoading } = useLoading();

  // バリデーション
  const {
    register,
    handleSubmit,
    setError,
    clearErrors,
    formState: { errors },
  } = useValidForm(loginSchema, {
    email: '',
    password: '',
  });

  async function onSubmit(formDatas: LoginSchema) {
    setLoading(true);
    clearErrors();

    try {
      const res = await login(formDatas);

      if (res.status === 200) {
        toast.success(res.response.message);
        router.push('/auth/mypage');
      } else if (res.status === 422) {
        const validationErrors = res.response.errors;
        Object.keys(validationErrors).forEach((fieldName) => {
          setError(fieldName as keyof LoginSchema, { types: validationErrors[fieldName] });
        });
      } else {
        console.error(res);
        toast.error(messages.serverError);
      }
    } catch (e) {
      console.log(e);
      toast.error(messages.networkError);
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className={cn('flex flex-col gap-4 w-full', className)} {...props}>
      <Card>
        <CardContent>
          <form onSubmit={(e) => void handleSubmit(onSubmit)(e)}>
            <FieldGroup>
              <TextInput
                id="email"
                type="email"
                label={`${fields.email}※`}
                placeholder="m@example.com"
                errors={errors?.email}
                {...register('email')}
              />
              <TextInput
                id="password"
                type="password"
                label={`${fields.password}※`}
                placeholder=""
                errors={errors?.password}
                autoComplete="off"
                {...register('password')}
              />
              <Field>
                <CustomButton type="submit">ログイン</CustomButton>
                <Separator />
                <FieldDescription className="text-center">
                  アカウント登録前の方はこちら <Link href="/users/create">ユーザ登録</Link>
                </FieldDescription>
              </Field>
            </FieldGroup>
          </form>
        </CardContent>
      </Card>
    </div>
  );
}
