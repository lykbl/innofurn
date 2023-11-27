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
        'p-2 h-max rounded text-center drop-shadow-md hover:drop-shadow-lg',
        {
          'border-transparent border hover:border-black': style === BUTTON_STYLES.DEFAULT,
          'bg-blue-500 hover:bg-blue-700 text-white': style === BUTTON_STYLES.BLUE,
        },
        className,
      ))}
      {...props}
    >
      {children}
    </button>
  )
}
