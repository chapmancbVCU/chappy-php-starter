import React from "react";
import Forms from "@chappy/components/Forms";
import {PasswordComplexityRequirements} from '@chappy/components/PasswordComplexityRequirements';
import route from "@chappy/utils/route";
import documentTitle from "@chappy/utils/documentTitle";

function ResetPassword({ user, errors, postAction }) {
    documentTitle("Reset Password");
    return (
        <div className="row align-items-center justify-content-center">
            <div className="col-md-6 bg-light p-3">
                <h3 className="text-center">Reset Password</h3>
                <PasswordComplexityRequirements />
                <form className="form" action="" method="post">
                    <Forms.CSRFInput />
                    <Forms.DisplayErrors errors={errors} />
                    <Forms.Input 
                        type="password"
                        label="Password"
                        name="password"
                        value={user.password}
                        inputAttrs={{className: "form-control input-sm"}}
                        divAttrs={{className: "form-group mb-3"}}
                    />
                    <Forms.Input 
                        type="password"
                        label="Confirm Password"
                        name="confirm"
                        value={user.confirm}
                        inputAttrs={{className: "form-control input-sm"}}
                        divAttrs={{className: "form-group mb-3"}}
                    />
                    <Forms.SubmitTag label="Set Password" inputAttrs={{className: "btn btn-primary"}} />
                </form>
            </div>
        </div>
    );
}        
export default ResetPassword;