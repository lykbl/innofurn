import {DetailedHTMLProps, InputHTMLAttributes} from "react";

const BasicInput = (props: DetailedHTMLProps<InputHTMLAttributes<HTMLInputElement>, HTMLInputElement>) => {
  return (
    <input
      {...props}
    />
  )
}

export default BasicInput;
