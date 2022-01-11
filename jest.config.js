module.exports = {
    globals: {},
    testEnvironment: "jsdom",
    transform: {
        "^.+\\.js$": "babel-jest",
        ".*\\.(vue)$": "@vue/vue3-jest",
    },
    moduleFileExtensions: ["vue", "js", "json", "jsx", "ts", "tsx", "node"],
    moduleNameMapper: {
        "^@/(.*)$": "<rootDir>/resources/js/$1",
    },
    collectCoverage: true,
    coverageReporters: ["lcov"],
    coverageDirectory: "results",
};
