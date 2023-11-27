import clsx from 'clsx';
import React from 'react';
import {twMerge} from "tailwind-merge";
import BaseLink from "next/link";

type LINK_PROPS = {
  className?: String,
  children?: React.ReactNode,
  href: string,
}

const Link = ({ className, children, href, ...props }: LINK_PROPS) => {
  return (
    <BaseLink
      className={twMerge(clsx(
        'hover:underline text-blue-500 hover:text-blue-700',
        className,
      ))}
      href={href}
      {...props}
    >
      {children}
    </BaseLink>
  )
}

export default Link;
