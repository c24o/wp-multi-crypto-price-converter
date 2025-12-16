/******/ (() => { // webpackBootstrap
/*!**************************************!*\
  !*** ./src/blocks/converter/view.ts ***!
  \**************************************/
/**
 * Frontend script for the Multi-Crypto Converter block.
 *
 * Handles:
 * - Fetching cryptocurrency prices from REST API every minute
 * - Updating the table with current prices
 * - Real-time conversion calculations based on USD input
 */

/**
 * State management for the converter block
 */
const converterState = {
  prices: {},
  updateInterval: null,
  isLoading: false,
  error: null
};

/**
 * Fetch prices from the REST API endpoint
 *
 * @param {string} coinsParam - Comma-separated coin symbols (e.g., "BTC,ETH")
 * @return {Promise<PricesMap>} Prices object keyed by coin symbol
 */
async function fetchPrices(coinsParam) {
  try {
    const response = await fetch(`/wp-json/mcc/v1/prices?coins=${coinsParam}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
      }
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
    console.error('Failed to fetch prices:', error);
    return {};
  }
}

/**
 * Calculate conversion amount
 *
 * @param {number} usdAmount - The USD amount to convert
 * @param {number} price - The price of the cryptocurrency in USD
 * @return {string} The converted amount formatted to 8 decimal places
 */
function calculateConversion(usdAmount, price) {
  if (!usdAmount || !price || price === 0) {
    return '0.00000000';
  }
  const amount = parseFloat(String(usdAmount));
  if (isNaN(amount)) {
    return '0.00000000';
  }
  const converted = amount / price;
  return converted.toFixed(8);
}

/**
 * Format price for display
 *
 * @param {number} price - The price in USD
 * @return {string} Formatted price string
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

/**
 * Create the HTML table with converter rows
 *
 * @param {string[]} coins - Array of coin symbols
 * @return {HTMLElement} The table wrapper element
 */
function createConverterTable(coins) {
  const tableWrapper = document.createElement('div');
  tableWrapper.className = 'mcc-converter-table-wrapper';
  const table = document.createElement('table');
  table.className = 'mcc-converter-table';

  // Create table header
  const thead = document.createElement('thead');
  const headerRow = document.createElement('tr');
  const thCoin = document.createElement('th');
  thCoin.className = 'mcc-converter-th-coin';
  thCoin.textContent = 'Cryptocurrency';
  const thPrice = document.createElement('th');
  thPrice.className = 'mcc-converter-th-price';
  thPrice.textContent = 'Price (USD)';
  const thAmount = document.createElement('th');
  thAmount.className = 'mcc-converter-th-amount';
  thAmount.textContent = 'Converted Amount';
  headerRow.appendChild(thCoin);
  headerRow.appendChild(thPrice);
  headerRow.appendChild(thAmount);
  thead.appendChild(headerRow);
  table.appendChild(thead);

  // Create table body with rows for each coin
  const tbody = document.createElement('tbody');
  coins.forEach(coin => {
    const row = document.createElement('tr');
    row.className = 'mcc-converter-row';
    row.setAttribute('data-coin', coin);
    const tdCoin = document.createElement('td');
    tdCoin.className = 'mcc-converter-td-coin';
    const coinSymbol = document.createElement('span');
    coinSymbol.className = 'mcc-coin-symbol';
    coinSymbol.textContent = coin;
    tdCoin.appendChild(coinSymbol);
    const tdPrice = document.createElement('td');
    tdPrice.className = 'mcc-converter-td-price';
    const priceSpan = document.createElement('span');
    priceSpan.className = 'mcc-coin-price';
    priceSpan.setAttribute('data-price', '');
    priceSpan.textContent = '—';
    tdPrice.appendChild(priceSpan);
    const tdAmount = document.createElement('td');
    tdAmount.className = 'mcc-converter-td-amount';
    const amountSpan = document.createElement('span');
    amountSpan.className = 'mcc-converted-amount';
    amountSpan.setAttribute('data-amount', '');
    amountSpan.textContent = '—';
    tdAmount.appendChild(amountSpan);
    row.appendChild(tdCoin);
    row.appendChild(tdPrice);
    row.appendChild(tdAmount);
    tbody.appendChild(row);
  });
  table.appendChild(tbody);
  tableWrapper.appendChild(table);
  return tableWrapper;
}

/**
 * Create and insert the USD input field at the top of the converter block
 *
 * @param {HTMLElement} block - The converter block element
 * @return {HTMLElement} The input element
 */
function createAndInsertInputField(block) {
  const inputGroup = document.createElement('div');
  inputGroup.className = 'mcc-converter-input-group';
  const label = document.createElement('label');
  label.className = 'mcc-converter-label';
  label.setAttribute('for', 'mcc-usd-amount');
  label.textContent = 'Amount (USD)';
  const input = document.createElement('input');
  input.id = 'mcc-usd-amount';
  input.type = 'number';
  input.className = 'mcc-converter-input';
  input.placeholder = '0.00';
  input.step = '0.01';
  input.min = '0';
  input.value = '1';
  input.setAttribute('data-type', 'currency-input');
  inputGroup.appendChild(label);
  inputGroup.appendChild(input);

  // Insert input group before the table wrapper
  const tableWrapper = block.querySelector('.mcc-converter-table-wrapper');
  if (tableWrapper) {
    block.insertBefore(inputGroup, tableWrapper);
  }
  return input;
}

/**
 * Update the converter table with current prices and conversions
 *
 * @param {HTMLElement} block - The converter block element
 * @param {PricesMap} prices - Object with coin symbols as keys and prices as values
 */
function updateConverterTable(block, prices) {
  const input = block.querySelector('[data-type="currency-input"]');
  const usdAmount = input ? input.value : '0';

  // Update each row with price and conversion
  block.querySelectorAll('.mcc-converter-row').forEach(row => {
    const coin = row.getAttribute('data-coin');
    const price = coin ? prices[coin] : undefined;

    // Update price cell
    const priceSpan = row.querySelector('[data-price]');
    if (priceSpan) {
      priceSpan.textContent = formatPrice(price || 0);
    }

    // Update conversion cell
    const amountSpan = row.querySelector('[data-amount]');
    if (amountSpan && price) {
      const converted = calculateConversion(usdAmount, price);
      amountSpan.textContent = converted;
    }
  });
}

/**
 * Initialize the converter block
 *
 * @param {HTMLElement} block - The converter block element
 */
function initConverterBlock(block) {
  // Get coins from data attribute
  const coinsData = block.querySelector('.mcc-converter-coins-data');
  if (!coinsData) {
    return;
  }
  const coinsString = coinsData.getAttribute('data-coins');
  if (!coinsString) {
    return;
  }

  // Parse coins from comma-separated string
  const coins = coinsString.split(',').map(coin => coin.trim()).filter(coin => coin.length > 0);
  if (coins.length === 0) {
    return;
  }

  // Build and insert the converter table
  const table = createConverterTable(coins);
  coinsData.replaceWith(table);

  // Create and insert the USD input field
  const input = createAndInsertInputField(block);
  const coinsParam = coins.join(',');

  // Fetch prices immediately
  fetchPrices(coinsParam).then(prices => {
    converterState.prices = prices;
    updateConverterTable(block, prices);
  });

  // Set up periodic updates (every 60 seconds)
  if (converterState.updateInterval) {
    clearInterval(converterState.updateInterval);
  }
  converterState.updateInterval = window.setInterval(() => {
    fetchPrices(coinsParam).then(prices => {
      converterState.prices = prices;
      updateConverterTable(block, prices);
    });
  }, 60000); // 60000ms = 1 minute

  // Handle USD input changes for real-time conversion
  if (input) {
    input.addEventListener('input', e => {
      const value = e.target.value;

      // Allow empty, 0, and positive numbers
      if (value === '' || value === '0' || parseFloat(value) > 0 && !isNaN(parseFloat(value))) {
        updateConverterTable(block, converterState.prices);
      }
    });
  }
}

/**
 * Initialize all converter blocks on the page
 */
document.addEventListener('DOMContentLoaded', () => {
  const blocks = document.querySelectorAll('.mcc-converter-block');
  blocks.forEach(block => {
    initConverterBlock(block);
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map