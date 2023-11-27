import {DetailedHTMLProps, InputHTMLAttributes} from "react";
import clsx from "clsx";
import {twMerge} from "tailwind-merge";

const BasicInput = ({ className, ...props}: DetailedHTMLProps<InputHTMLAttributes<HTMLInputElement>, HTMLInputElement>) => {
  return (
    <input
      className={twMerge(clsx(
        className,
      ))}
      {...props}
    />
  )
}

export default BasicInput;
