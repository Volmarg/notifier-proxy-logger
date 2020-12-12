const APP_ENV_DEV  = "DEV";
const APP_ENV_PROD = "PROD";

let VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING = "...";
let VUE_APP_ENV                               = APP_ENV_DEV;

function isDev()
{
    return ( VUE_APP_ENV === APP_ENV_DEV );
}

export {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING, isDev};