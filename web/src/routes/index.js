import React from 'react';
import { BrowserRouter, Switch, Route } from 'react-router-dom';

import { Login, Register, Transaction, TransactionSuccess } from '../pages';
import WrappedRoute from './WrappedRoute';

const Routes = () => (
    <BrowserRouter>
        <Switch>
            <WrappedRoute exact path="/login" isPrivate={false} component={Login}/>
            <WrappedRoute exact path="/register" isPrivate={false} component={Register}/>
            <WrappedRoute exact path="/transaction" isPrivate={true} component={Transaction}/>
            <WrappedRoute exact path="/transaction/success" isPrivate={true} component={TransactionSuccess}/>
            <Route path="*" component={() => <div>error</div>}/>
        </Switch>
    </BrowserRouter>
);

export default Routes;