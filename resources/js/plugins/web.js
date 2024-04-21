import AuthLayout from '../layouts/auth-layout';
import WebLayout from '../layouts/web-layout';
import Register from '../components/auth/register';
import Login from '../components/auth/login';
import ForgetPassword from '../components/auth/forget-password';
import ResetPassword from '../components/auth/reset-password';
import EmailVerification from '../components/web/email-verification';
import Profile from '../components/web/profile';
import Home from '../components/web/home';
import Chat from '../components/web/chat';
import AuthenticatedGuard from "../shared/guards/authenticated-guard";
import GuestGuard from "../shared/guards/guest-guard";
export default [
    {
        path: "",
        redirect: "/home"
    },
    {
        path: "",
        component: AuthLayout,
        beforeEnter: [
            GuestGuard
        ],
        children: [
            { path: "register", component: Register },
            { path: "login", component: Login },
            { path: "forget-password", component: ForgetPassword },
            { path: "reset-password/:token", component: ResetPassword },
        ]
    },
    {
        path: "",
        component: WebLayout,
        children: [
            { path: "home", component: Home },
        ]
    },
    {
        path: "",
        component: WebLayout,
        beforeEnter: [
            AuthenticatedGuard
        ],
        children: [
            { path: "chat", component: Chat },
            { path: "verify-email", component: EmailVerification },
            { path: "profile", component: Profile },
        ]
    },
];