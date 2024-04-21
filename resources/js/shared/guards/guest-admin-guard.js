import TokenUtil from "../utils/token-util";
import adminClient from '../../http-clients/admin/admin-auth-client';
import global from "../global";
export default (to, from, next) => {
    if (!TokenUtil.get()) {
        next()
    }
    else {
        return adminClient.verifyToken()
            .then(() => { next({ path: global.ADMIN_AUTH_REDIRECT }) })
            .catch(() => { next() })
    }
}
