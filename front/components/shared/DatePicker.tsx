'use client';

import * as React from 'react';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Field, FieldLabel } from '@/components/ui/field';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';

type Props = {
  id: string;
  label?: string;
  buttonInitialText: string;
  fieldWidth?: number;
  //   errors?: FieldError;
  //   autoComplete?: 'on' | 'off';
  //   onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
  //   onBlur?: (e: React.FocusEvent<HTMLInputElement>) => void;
};

export default function DatePicker(props: Props) {
  const [open, setOpen] = React.useState(false);
  const [date, setDate] = React.useState<Date | undefined>(undefined);
  const { id, label, buttonInitialText, fieldWidth } = props;

  return (
    <Field className={`w-${fieldWidth}`}>
      <FieldLabel htmlFor="date">{label}</FieldLabel>
      <Popover open={open} onOpenChange={setOpen}>
        <PopoverTrigger asChild>
          <Button variant="outline" id={id} name={id} className="justify-start font-normal">
            {date ? date.toLocaleDateString() : buttonInitialText}
          </Button>
        </PopoverTrigger>{' '}
        <PopoverContent className="w-auto overflow-hidden p-0" align="start">
          <Calendar
            mode="single"
            selected={date}
            defaultMonth={date}
            captionLayout="dropdown"
            onSelect={(date: Date) => {
              setDate(date);
              setOpen(false);
            }}
          />
        </PopoverContent>
      </Popover>
      <input type="hidden" name={id} value={date ? date.toISOString() : undefined} />
    </Field>
  );
}
