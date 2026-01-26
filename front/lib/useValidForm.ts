import { zodResolver } from '@hookform/resolvers/zod';
import { type DefaultValues, type UseFormReturn, useForm } from 'react-hook-form';
import type { ZodType, z } from 'zod';

// TODO: サプレッションコメントを許可するかどうか検討
// biome-ignore lint/suspicious/noExplicitAny: 型定義が動的なため許可
export function useValidForm<T extends ZodType<any, any, any>>(
  schema: T,
  defaultValues: DefaultValues<z.infer<T>>,
): UseFormReturn<z.infer<T>> {
  type TFormValues = z.infer<T>;

  return useForm<TFormValues>({
    mode: 'onSubmit',
    reValidateMode: 'onSubmit',
    criteriaMode: 'all',
    resolver: zodResolver<TFormValues, any, TFormValues>(schema),
    defaultValues,
  }) as UseFormReturn<z.infer<T>>;
}
