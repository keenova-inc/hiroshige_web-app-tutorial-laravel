import type React from 'react';
import { forwardRef } from 'react';
import type { FieldError } from 'react-hook-form';
import { Field, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';

type InputProps = {
  id: string;
  type: string;
  label?: string;
  placeholder?: string;
  required?: boolean;
  errors?: FieldError;
  className?: string;
  autoComplete?: 'on' | 'off';
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
  onBlur?: (e: React.FocusEvent<HTMLInputElement>) => void;
};

const TextInput = forwardRef<HTMLInputElement, InputProps>((props, ref) => {
  const { label, id, errors, ...inputAttributes } = props;

  return (
    <Field data-invalid={errors !== undefined}>
      <FieldLabel htmlFor={id}>{label}</FieldLabel>
      <div>
        <Input
          id={id}
          name={id}
          {...inputAttributes}
          ref={ref}
          aria-invalid={errors !== undefined}
        />

        {errors?.types &&
          Object.values(errors.types).map((errMsg, _) => (
            <p key={`${errMsg}`} className="mt-1 text-xs text-destructive">
              ・{errMsg}
            </p>
          ))}
      </div>
    </Field>
  );
});

export default TextInput;
