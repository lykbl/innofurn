import clsx from 'clsx';
import React, { MouseEventHandler } from 'react';
import {twMerge} from "tailwind-merge";

export enum BUTTON_STYLES {
  DEFAULT,
  BLUE,
}

type BUTTON_PROPS = {
  style?: BUTTON_STYLES,
  className?: String,
  children?: React.ReactNode,
  onClick?: MouseEventHandler<HTMLButtonElement> | undefined,
}

export function Button({ style = BUTTON_STYLES.DEFAULT, className, children, ...props }: BUTTON_PROPS) {
  return (
    <button
      className={twMerge(clsx(
        'p-2 h-max rounded text-center drop-shadow-md hover:drop-shadow-lg text-white font-medium',
        {
          'bg-neutral-950 hover:bg-neutral-700': style === BUTTON_STYLES.DEFAULT,
          'bg-blue-600 hover:bg-blue-700': style === BUTTON_STYLES.BLUE,
        },
        className,
      ))}
      {...props}
    >
      {children}
    </button>
  )
}
