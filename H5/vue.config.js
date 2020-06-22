const path = require("path");

function resolve(dir) {
    return path.join(__dirname, dir);
}

module.exports = {
    // mode: 'production'
    assetsDir: "static/wap",
    productionSourceMap:false,
    publicPath: '/',
    devServer: {
       // port: 8080, // 端口号
        //host: 'localhost',
        //https: false, // https:{type:Boolean}
        open: true
    },
    css: {
        loaderOptions: {
            sass: {
                //prependData: `@import "~@/assets/scss/scss_variable.scss";`
            }
        }
    },
    chainWebpack: config => {
        config.plugin('html').tap(args => {
            args[0].title= 'A3Mall';
            return args;
        });
    }
}