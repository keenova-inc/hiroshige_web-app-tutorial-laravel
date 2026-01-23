import { zodResolver } from '@hookform/resolvers/zod';
import { type DefaultValues, type UseFormReturn, useForm } from 'react-hook-form';
import type { ZodType, z } from 'zod';

// T の制約を ZodTypeAny または ZodType に変更します
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
