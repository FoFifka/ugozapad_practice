module.exports = {
    transpileDependencies: ["vuetify"],
    devServer: {
        proxy: "http://ugozapad_practice.test/"
    },
    outputDir: "../public/",
    publicPath: "/",
    indexPath:
        process.env.NODE_ENV === "production"
            ? "../resources/views/index.blade.php"
            : "index.html"
};
