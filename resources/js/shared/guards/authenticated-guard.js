import TokenUtil from "../utils/token-util";
import authClient from '../../http-clients/web/auth-client';
import global from "../global";
export default (to, from, next) => {
    if (!TokenUtil.get()) {
        next({ path: global.GUEST_REDIRECT })
    }
    else {
        return authClient.verifyToken()
            .then(() => { next() })
            .catch(() => { next({ path: global.GUEST_REDIRECT }) })
    }
}