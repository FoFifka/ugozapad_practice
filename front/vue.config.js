module.exports = {
    transpileDependencies: ["vuetify"],
    devServer: {
        proxy: "http://localhost:8000"
    },
    outputDir: "../public/",
    publicPath: "/",
    indexPath:
        process.env.NODE_ENV === "production"
            ? "../resources/views/index.blade.php"
            : "index.html"

};
