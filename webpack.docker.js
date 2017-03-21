// See https://github.com/nodejs/node/issues/4182
module.exports = {
    register () {
        process.on('SIGINT', process.exit);
    }
};
