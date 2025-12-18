/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blocks/converter/FrontendConverter.tsx":
/*!****************************************************!*\
  !*** ./src/blocks/converter/FrontendConverter.tsx ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ FrontendConverter)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);
/**
 * React component for the frontend view of the Multi-Crypto Converter block.
 */



const UPDATE_INTERVAL_MS = 60000;

/**
 * Fetch prices from the REST API endpoint.
 *
 * @param {string} coinsParam - Comma-separated coin symbols.
 * @return {Promise<PricesMap>} A map of coin symbols to their prices in USD.
 */
async function fetchPrices(coinsParam) {
  try {
    const response = await fetch(`/wp-json/mcc/v1/prices?coins=${coinsParam}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    if (data.success && data.data) {
      const pricesMap = {};
      data.data.forEach(item => {
        pricesMap[item.symbol] = parseFloat(item.price_usd);
      });
      return pricesMap;
    }
    throw new Error(data.message || 'Invalid API response');
  } catch (error) {
    console.error('Failed to fetch prices:', error);
    return {};
  }
}

/**
 * Calculate the converted amount.
 *
 * @param {number} usdAmount - The amount in USD.
 * @param {number} price - The price of the cryptocurrency.
 * @return {number} The converted amount, formatted to 8 decimal places.
 */
function calculateConversion(usdAmount, price) {
  if (!usdAmount || !price || price === 0) {
    return 0;
  }
  return usdAmount / price;
}

/**
 * Format the converted amount to display it.
 *
 * @param {number} amount - The amount to format.
 * @return {string} The amount formatted to 8 decimal places.
 */
function formatConvertedAmount(amount) {
  return amount ? amount.toFixed(8) : '';
}

/**
 * Format a number as a USD currency string.
 *
 * @param {number} price - The price to format.
 * @return {string} The formatted price string.
 */
function formatPrice(price) {
  if (!price || price === 0) {
    return '—';
  }
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(price);
}
function FrontendConverter({
  coins
}) {
  const [prices, setPrices] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({});
  const [amounts, setAmounts] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({
    'usd': 1
  });
  const [lastBaseCoin, setLastBaseCoin] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)('usd');
  const [isLoading, setIsLoading] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const coinsParam = coins.join(',');
    const updatePrices = async () => {
      const fetchedPrices = await fetchPrices(coinsParam);
      setPrices(fetchedPrices);
      setIsLoading(false);
    };

    // Fetch prices immediately and then set an interval.
    updatePrices();
    const intervalId = setInterval(updatePrices, UPDATE_INTERVAL_MS);

    // Cleanup interval on component unmount.
    return () => clearInterval(intervalId);
  }, [coins]);

  // Recalculate conversions when prices are updated.
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    convertCoinsAmounts(lastBaseCoin, amounts[lastBaseCoin]);
  }, [prices]);

  /**
   * Convert the amounts of the coins to the equivalent amount of the base
   * coin used.
   *
   * @param {string} baseCoin - The coin to use as a base.
   * @param {number} amountOverride - The new amount for the base coin.
   */
  const convertCoinsAmounts = (baseCoin, amountOverride) => {
    setLastBaseCoin(baseCoin);
    const newAmounts = {
      ...amounts
    };
    newAmounts[baseCoin] = amountOverride;

    // Convert the current amount to USD.
    const usdAmount = 'usd' !== baseCoin ? amountOverride * prices[baseCoin] : amountOverride;
    if ('usd' !== baseCoin) {
      newAmounts.usd = usdAmount;
    }

    // Convert all the other coins.
    for (const [targetCoin, targetPrice] of Object.entries(prices)) {
      if (baseCoin !== targetCoin) {
        newAmounts[targetCoin] = calculateConversion(usdAmount, targetPrice);
      }
    }
    setAmounts(newAmounts);
  };

  /**
   * Handler of the coin amounts changes.
   *
   * @param e - The input changed.
   * @param coin - The coin being changed.
   */
  const handleAmountChange = (e, coin) => {
    const value = e.target.value;
    // Allow empty input to be treated as 0, otherwise parse as float.
    const newAmount = value === '' ? 0 : parseFloat(value);
    convertCoinsAmounts(coin, newAmount);
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    className: "mcc-converter-wrapper",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("table", {
      className: "mcc-converter-table",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("thead", {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("tr", {
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("th", {
            className: "mcc-converter-th-coin",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Cryptocurrency', 'multi-crypto-convert')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("th", {
            className: "mcc-converter-th-price",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Price (USD)', 'multi-crypto-convert')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("th", {
            className: "mcc-converter-th-amount",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Converted Amount', 'multi-crypto-convert')
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("tr", {
          className: "mcc-converter-row",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
              className: "mcc-coin-symbol",
              children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('USD', 'multi-crypto-convert')
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
              className: "mcc-coin-price",
              children: formatPrice(1)
            })
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("input", {
              id: "mcc-usd-amount",
              type: "number",
              className: "mcc-converter-input",
              value: 'usd' === lastBaseCoin ? amounts.usd : amounts.usd.toFixed(2),
              onChange: e => handleAmountChange(e, 'usd'),
              placeholder: "1.00",
              step: "0.01",
              min: "0"
            })
          })]
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("tbody", {
        children: isLoading ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("tr", {
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
            colSpan: 3,
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Loading prices...', 'multi-crypto-convert')
          })
        }) : coins.map(coin => {
          const price = prices[coin];
          const isCurrentCoin = coin === lastBaseCoin;
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("tr", {
            className: "mcc-converter-row",
            "data-coin": coin,
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
              className: "mcc-converter-td-coin",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
                className: "mcc-coin-symbol",
                children: coin.toUpperCase()
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
              className: "mcc-converter-td-price",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("span", {
                className: "mcc-coin-price",
                children: formatPrice(price)
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
              className: "mcc-converter-td-amount",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("input", {
                type: "number",
                className: "mcc-converted-amount",
                value: isCurrentCoin ? amounts[coin] : formatConvertedAmount(amounts[coin]),
                onChange: e => handleAmountChange(e, coin),
                placeholder: "-",
                step: "0.00000001",
                min: "0"
              })
            })]
          }, coin);
        })
      })]
    })
  });
}

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!***************************************!*\
  !*** ./src/blocks/converter/view.tsx ***!
  \***************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _FrontendConverter__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./FrontendConverter */ "./src/blocks/converter/FrontendConverter.tsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);
/**
 * Frontend script for the Multi-Crypto Converter block. This file serves as the
 * entry point for rendering the React-based frontend component.
 *
 * It finds all instances of the converter block on the page and mounts the
 * `FrontendConverter` component into them, passing the selected coins as a prop.
 */



/**
 * Initialize all converter blocks on the page
 */

function initializeBlocks() {
  const blockWrappers = document.querySelectorAll('.wp-block-multi-crypto-convert-converter');
  blockWrappers.forEach(div => {
    const coinsString = div.dataset.coins;
    if (coinsString) {
      const coins = coinsString.split(',').map(coin => coin.trim()).filter(Boolean);
      if (coins.length > 0) {
        const root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createRoot)(div);
        root.render(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_FrontendConverter__WEBPACK_IMPORTED_MODULE_1__["default"], {
          coins: coins
        }));
      }
    }
  });
}

// Initialize blocks on DOMContentLoaded.
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeBlocks);
} else {
  initializeBlocks();
}
})();

/******/ })()
;
//# sourceMappingURL=view.js.map