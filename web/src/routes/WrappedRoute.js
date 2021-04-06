import React from 'react';
import { Route, Redirect } from 'react-router-dom';

const WrappedRoute = ({
    component: Component,
    isPrivate,
    ...rest
}) => {

    const loading = false;
    const signed = false;

    if (!signed && isPrivate) { 
        return (<Redirect to="/signin" />);
    }

    if (signed && !isPrivate) { 
        return (<Redirect to="/transaction" />);
    }

    return (
        <Route {...rest} render={props => (
            <Component {...props} />
        )}/>
    );
}

export default WrappedRoute;