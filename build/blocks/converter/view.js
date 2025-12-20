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
 * React component for the frontend view of the Multi Crypto Price Converter block.
 */



const UPDATE_INTERVAL_MS = 60000;

/**
 * Fetch prices from the REST API endpoint.
 *
 * @param {string} coinsParam - Comma-separated coin symbols.
 * @param {AbortSignal} [signal] - Optional abort signal for the fetch request.
 * @return {Promise<PricesMap>} A map of coin symbols to their prices in USD.
 */
async function fetchPrices(coinsParam, signal) {
  try {
    const response = await fetch(`/wp-json/mcc/v1/prices?coins=${coinsParam}`, {
      signal
    });
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
    if (error instanceof Error && error.name === 'AbortError') {
      throw error;
    }
    console.error('Failed to fetch prices:', error);
    return {};
  }
}

/**
 * Format the converted amount to display it.
 *
 * @param {string} coin - The coin to display.
 * @param {number} amount - The amount to format.
 * @return {string} The amount formatted to 8 decimal places.
 */
function formatConvertedAmount(coin, amount) {
  if (!amount || !coin) return '';
  if ('usd' === coin) return amount.toFixed(2);
  return amount.toFixed(8);
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
  const [baseCoin, setBaseCoin] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)('usd');
  const [baseAmount, setBaseAmount] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(1);
  const [isLoading, setIsLoading] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const [error, setError] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const controller = new AbortController();
    const coinsParam = coins.join(',');
    let timeoutId;
    const updatePrices = async () => {
      try {
        const fetchedPrices = await fetchPrices(coinsParam, controller.signal);
        // Only update if we have data, otherwise keep old prices or show error
        if (Object.keys(fetchedPrices).length > 0) {
          setPrices(fetchedPrices);
          setError(false);
        } else {
          setError(true);
        }
      } catch (err) {
        // Ignore abort errors
        if (err instanceof Error && err.name !== 'AbortError') {
          setError(true);
        }
      } finally {
        if (!controller.signal.aborted) {
          setIsLoading(false);
          // Schedule the next update only after the current one finishes (Recursive Polling)
          timeoutId = setTimeout(updatePrices, UPDATE_INTERVAL_MS);
        }
      }
    };

    // Start the polling loop.
    updatePrices();

    // Cleanup timeout and abort fetch on component unmount/update.
    return () => {
      clearTimeout(timeoutId);
      controller.abort();
    };
  }, [coins]);

  /**
   * Calculates the display value for a specific coin based on the current base coin.
   *
   * @param {string} targetCoin - The coin to calculate for.
   * @return {string | number} The calculated amount or the raw input if it's the base.
   */
  const getDisplayAmount = targetCoin => {
    if (baseAmount === '' || baseAmount === 0) return '';
    if (targetCoin === baseCoin) return baseAmount;
    if ('usd' !== baseCoin && !prices[baseCoin]) return '';
    if ('usd' !== targetCoin && !prices[targetCoin]) return '';
    const usdValue = 'usd' === baseCoin ? baseAmount : baseAmount * prices[baseCoin];
    const targetValue = 'usd' === targetCoin ? usdValue : usdValue / prices[targetCoin];
    return formatConvertedAmount(targetCoin, targetValue);
  };

  /**
   * Focus the input when clicking on the row cells.
   *
   * @param {React.MouseEvent<HTMLTableRowElement>} e - The click event.
   */
  const handleRowClick = e => {
    if ('INPUT' === e.target.tagName) {
      return;
    }
    const input = e.currentTarget.querySelector('input');
    if (input) {
      input.focus();
    }
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("figure", {
    className: "wp-block-table is-style-stripes",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("table", {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("thead", {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("tr", {
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("th", {
            scope: "col",
            className: "mcpc-converter-th-coin",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Coin', 'multi-crypto-price-converter')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("th", {
            scope: "col",
            className: "mcpc-converter-th-price",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Price (USD)', 'multi-crypto-price-converter')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("th", {
            scope: "col",
            className: "mcpc-converter-th-amount",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Amount', 'multi-crypto-price-converter')
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("tr", {
          className: `mcpc-converter-row${'usd' === baseCoin ? ' mcpc-row-active' : ''}`,
          onClick: handleRowClick,
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('USD', 'multi-crypto-price-converter')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
            children: formatPrice(1.0) /* USD is always 1 USD */
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("input", {
              id: "mcpc-usd-amount",
              type: "number",
              className: "mcpc-amount-input",
              "aria-label": (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Amount in USD', 'multi-crypto-price-converter'),
              value: getDisplayAmount('usd'),
              onChange: e => {
                setBaseCoin('usd');
                setBaseAmount(e.target.value === '' ? '' : parseFloat(e.target.value));
              },
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
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Loading prices...', 'multi-crypto-price-converter')
          })
        }) : error ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("tr", {
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
            colSpan: 3,
            className: "mcpc-error",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Error loading prices.', 'multi-crypto-price-converter')
          })
        }) : coins.map(coin => {
          const price = prices[coin];
          const isCurrentCoin = coin === baseCoin;
          const inputId = `mcpc-amount-${coin}`;
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("tr", {
            "data-coin": coin,
            className: isCurrentCoin ? 'mcpc-row-active mcpc-converter-row' : 'mcpc-converter-row',
            onClick: handleRowClick,
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
              children: coin.toUpperCase()
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
              children: formatPrice(price)
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("td", {
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("input", {
                id: inputId,
                type: "number",
                "aria-label": `Amount in ${coin.toUpperCase()}`,
                className: "mcpc-amount-input",
                value: getDisplayAmount(coin),
                onChange: e => {
                  setBaseCoin(coin);
                  setBaseAmount(e.target.value === '' ? '' : parseFloat(e.target.value));
                },
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
  const blockWrappers = document.querySelectorAll('.wp-block-multi-crypto-price-converter-converter');
  blockWrappers.forEach(div => {
    const coinsString = div.dataset.coins;
    if (!coinsString) {
      return;
    }
    const coins = coinsString.split(',').map(coin => coin.trim()).filter(Boolean);
    if (0 === coins.length) {
      return;
    }
    const container = div.querySelector('.mcpc-converter-container');
    if (container) {
      const root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createRoot)(container);
      root.render(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_FrontendConverter__WEBPACK_IMPORTED_MODULE_1__["default"], {
        coins: coins
      }));
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