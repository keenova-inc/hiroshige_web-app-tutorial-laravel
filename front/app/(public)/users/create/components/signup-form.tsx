'use client';

import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { OkButton } from '@/components/shared/CustomButton';
import TextInput from '@/components/shared/TextInput';
// import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Field, FieldDescription, FieldGroup } from '@/components/ui/field';
import { Separator } from '@/components/ui/separator';
import { useLoading } from '@/contexts/LoadingContext';
import { max as emailMaxCount } from '@/features/users/schemas/email';
import { min as passwordMinCount } from '@/features/users/schemas/password';
import { max as userNameMaxCount } from '@/features/users/schemas/userName';
import fields from '@/lang/ja/fields';
import messages from '@/lang/ja/messages';
import { useValidForm } from '@/lib/useValidForm';
import { cn } from '@/lib/utils';
import { createUser } from '../actions';
import { type CreateUserSchema, createUserSchema } from '../schemas';

export function SignupForm({ className, ...props }: React.ComponentProps<'div'>) {
  const { setLoading } = useLoading();

  // バリデーション
  const {
    register,
    handleSubmit,
    setError,
    clearErrors,
    formState: { errors },
  } = useValidForm(createUserSchema, {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  const router = useRouter();

  async function onSubmit(formDatas: CreateUserSchema) {
    setLoading(true);
    clearErrors();
    try {
      const res = await createUser(formDatas);
      // console.log('******** submitted ********');
      // console.log('res: ', res);

      if (res.status === 201) {
        toast.success(res.response.message);
        router.push('/login');
      } else if (res.status === 422) {
        const validationErrors = res.response.errors;
        Object.keys(validationErrors).forEach((fieldName) => {
          setError(fieldName as keyof CreateUserSchema, { types: validationErrors[fieldName] });
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
                id="name"
                type="text"
                label={`${fields.userName}※（最大${userNameMaxCount}文字）`}
                placeholder="John Doe"
                errors={errors?.name}
                {...register('name')}
              />
              <TextInput
                id="email"
                type="email"
                label={`${fields.email}※（最大${emailMaxCount}文字）`}
                placeholder="m@example.com"
                errors={errors?.email}
                {...register('email')}
              />
              <TextInput
                id="password"
                type="password"
                label={`${fields.password}※（${passwordMinCount}文字以上）`}
                placeholder=""
                errors={errors?.password}
                autoComplete="off"
                {...register('password')}
              />
              <TextInput
                id="password_confirmation"
                type="password"
                label={`${fields.confirmPassword}※`}
                placeholder=""
                errors={errors?.password_confirmation}
                autoComplete="off"
                {...register('password_confirmation')}
              />
              <Field>
                <OkButton type="submit">登録</OkButton>
                <Separator />
                <FieldDescription className="text-center">
                  アカウント登録済の方はこちら <Link href="/login">ログイン</Link>
                </FieldDescription>
              </Field>
            </FieldGroup>
          </form>
        </CardContent>
      </Card>
    </div>
  );
}
