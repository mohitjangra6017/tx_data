import {langString} from 'tui/i18n';

/**
     * Calculate and return a value within a given range.
     *
     * @param val
     * @param min
     * @param max
     * @returns {number}
     */
function normaliseIntoRange(val, min, max) {
    return Math.min(max, Math.max(min, val));
}

function isValidFontSize(val, resolver) {

    let resolvedValue = resolver(val);

    let keywords = [
        "xx-small",
        "x-small",
        "small",
        "medium",
        "large",
        "x-large",
        "xx-large",
        "xxx-large",
        "smaller",
        "larger",
        "inherit",
        "initial",
        "unset"
    ];
    let isKeyword = keywords.includes(resolvedValue);
    let isValidMeasurement = /^\d+(\.\d+)?(ch|em|ex|rem|vh|vw|vmin|vmax|px|cm|mm|in|pc|pt|%)$/.test(resolvedValue);
    return resolvedValue === "0" || isValidMeasurement || isKeyword;
}

function isValidSize(val, resolver) {
    let resolvedValue = resolver(val);
    let isValidMeasurement = /^-?\d+(\.\d+)?(ch|em|ex|rem|vh|vw|vmin|vmax|px|cm|mm|in|pc|pt|%)$/.test(resolvedValue);
    return resolvedValue === "0" || isValidMeasurement;
}

function isValidPixelSize(val, resolver) {
  let resolvedValue = resolver(val);
  let isValidMeasurement = /^-?\d+(\.\d+)?px$/.test(resolvedValue);
  return resolvedValue === "0" || isValidMeasurement;
}

function isAboveMinValue(val, minValue, resolver) {
  let resolvedValue = resolver(val);
  return Number.isNaN(resolvedValue) === false && Number.parseFloat(resolvedValue) >= minValue;
}

function isBelowMaxValue(val, maxValue, resolver) {
  let resolvedValue = resolver(val);
  return Number.isNaN(resolvedValue) === false && Number.parseFloat(resolvedValue) <= maxValue;
}

/**
 * Keys added to the customValidation object which define validation types need to match the keys defined within the
 * validation array, which is part of a setting definition.  These definitions are found in the settings json files.
 *
 * When adding keys for validation types, use underscores for spaces.
 */
export const customValidation = {
    allThemeVariables: {},
    font_size: () => ({
        validate: val => isValidFontSize(val, customValidation.resolveSetting),
        message: () => langString('invalid_font_size', 'theme_kineo')
    }),
    size: () => ({
        validate: val => isValidSize(val, customValidation.resolveSetting),
        message: () => langString('invalid_size', 'theme_kineo')
    }),
    colour: () => ({
        validate: val => {
            let settingsResolver = customValidation.resolveSetting;
            let setting = customValidation.resolveSetting(val, 0);
            let isHex = customValidation.isValidHexColour(setting, settingsResolver);
            let isRgb = customValidation.isValidRgbColour(setting, settingsResolver);
            let isRgba = customValidation.isValidRgbaColour(setting, settingsResolver);
            let isKeyword = customValidation.isValidKeywordColour(setting, settingsResolver);
            return isHex || isRgb || isRgba || isKeyword;
        },
        message: () => langString('invalid_colour', 'theme_kineo')
    }),
    pixel: () => ({
      validate: val => isValidPixelSize(val, customValidation.resolveSetting),
      message: () => langString('invalid_pixel_size', 'theme_kineo')
    }),
    min_number: minValue => ({
      validate: val => isAboveMinValue(val, minValue, customValidation.resolveSetting),
      message: () => langString('invalid_min_value', 'theme_kineo', minValue)
    }),
    max_number: maxValue => ({
      validate: val => isBelowMaxValue(val, maxValue, customValidation.resolveSetting),
      message: () => langString('invalid_max_value', 'theme_kineo', maxValue)
    }),
    initSettings: (allThemeVariables) => {
        customValidation.allThemeVariables = allThemeVariables;
    },

    /**
     *
     * @param value: the setting value to resolve
     * @param depth: recursion depth counter
     * @param settingId (optional) current setting ID
     * @returns {string|null}
     */

    resolveSetting: (value, depth) => {

        // If not an '@' variable, return the value as it doesn't need to be resolved.
        if (value.indexOf('@') !== 0) {
            return value;
        }

        if (isNaN(depth)) {
            depth = 0;
        }

        if (depth > 100) {
            return null;
        }

        // We now have an '@' variable which needs resolving.
        // Remove the '@' to make a valid setting identifier.
        let identifier = value.replace('@', '');

        // Get the most up-to-date object representing this setting.
        let setting = customValidation.allThemeVariables[identifier];

        if (typeof setting === 'undefined') {
            return null;
        }

        // At this point, we have a setting object although the value might still be an '@' variable.
        // Call resolveSetting again with the new value to see if it needs to be resolved.
        return customValidation.resolveSetting(setting.value, depth + 1);

    },
    getRgbRegEx: () => /^rgb\s?\(\s?(?<r>\d{1,3})\s?,\s?(?<g>\d{1,3})\s?,\s?(?<b>\d{1,3})\s?\)$/,
    getRgbaRegEx: () => /^rgba\s?\(\s?(?<r>\d{1,3})\s?,\s?(?<g>\d{1,3})\s?,\s?(?<b>\d{1,3})\s?,\s?(?<a>0\.?([0-9]|[0-9]+)?|1|[0-9]{1,3}%)\)$/,
    isValidHexColour: (val, resolver) => {
        if (val === null) {
            return false;
        }
        if (typeof resolver === 'undefined') {
            resolver = customValidation.resolveSetting;
        }
        let resolvedSetting = resolver(val);
        return /^#[0-9A-F]{6}$/i.test(resolvedSetting);
    },
    isValidRgbColour(val, resolver) {
        if (val === null) {
            return false;
        }
        if (typeof resolver === 'undefined') {
            resolver = customValidation.resolveSetting;
        }

        let resolvedSetting = resolver(val);
        let matches = resolvedSetting.match(customValidation.getRgbRegEx());
        if (matches === null) {
            return false;
        }

        let r = parseInt(matches.groups.r);
        let g = parseInt(matches.groups.g);
        let b = parseInt(matches.groups.b);
        return matches
            && r === normaliseIntoRange(r, 0, 255)
            && g === normaliseIntoRange(g, 0, 255)
            && b === normaliseIntoRange(b, 0, 255);

    },
    isValidRgbaColour: (val, resolver) => {

        if (val === null) {
            return false;
        }

        if (typeof resolver === 'undefined') {
            resolver = customValidation.resolveSetting;
        }

        let resolvedSetting = resolver(val);
        let matches = resolvedSetting.match(customValidation.getRgbaRegEx());
        if (matches === null) {
            return false;
        }

        let r = parseInt(matches.groups.r);
        let g = parseInt(matches.groups.g);
        let b = parseInt(matches.groups.b);
        let a = parseInt(matches.groups.a);
        return matches
            && r === normaliseIntoRange(r, 0, 255)
            && g === normaliseIntoRange(g, 0, 255)
            && b === normaliseIntoRange(b, 0, 255)
            && a === normaliseIntoRange(a, 0, 1);
    },
    isValidKeywordColour(val, resolver) {
        if (val === null) {
            return false;
        }
        if (typeof resolver === 'undefined') {
            resolver = customValidation.resolveSetting;
        }
        let resolvedSetting = resolver(val);
        return Object.keys(customValidation.getKeywordColours()).filter(colour => colour === resolvedSetting).length === 1;
    },
    getKeywordColours: () => {
        return {
            "transparent": "#ffffff",
            "black": "#000000",
            "silver": "#c0c0c0",
            "gray": "#808080",
            "white": "#ffffff",
            "maroon": "#800000",
            "red": "#ff0000",
            "purple": "#800080",
            "fuchsia": "#ff00ff",
            "green": "#008000",
            "lime": "#00ff00",
            "olive": "#808000",
            "yellow": "#ffff00",
            "navy": "#000080",
            "blue": "#0000ff",
            "teal": "#008080",
            "aqua": "#00ffff",
            "orange": "#ffa500",
            "aliceblue": "#f0f8ff",
            "antiquewhite": "#faebd7",
            "aquamarine": "#7fffd4",
            "azure": "#f0ffff",
            "beige": "#f5f5dc",
            "bisque": "#ffe4c4",
            "blanchedalmond": "#ffebcd",
            "blueviolet": "#8a2be2",
            "brown": "#a52a2a",
            "burlywood": "#deb887",
            "cadetblue": "#5f9ea0",
            "chartreuse": "#7fff00",
            "chocolate": "#d2691e",
            "coral": "#ff7f50",
            "cornflowerblue": "#6495ed",
            "cornsilk": "#fff8dc",
            "crimson": "#dc143c",
            "cyan": "#00ffff",
            "darkblue": "#00008b",
            "darkcyan": "#008b8b",
            "darkgoldenrod": "#b8860b",
            "darkgray": "#a9a9a9",
            "darkgreen": "#006400",
            "darkgrey": "#a9a9a9",
            "darkkhaki": "#bdb76b",
            "darkmagenta": "#8b008b",
            "darkolivegreen": "#556b2f",
            "darkorange": "#ff8c00",
            "darkorchid": "#9932cc",
            "darkred": "#8b0000",
            "darksalmon": "#e9967a",
            "darkseagreen": "#8fbc8f",
            "darkslateblue": "#483d8b",
            "darkslategray": "#2f4f4f",
            "darkslategrey": "#2f4f4f",
            "darkturquoise": "#00ced1",
            "darkviolet": "#9400d3",
            "deeppink": "#ff1493",
            "deepskyblue": "#00bfff",
            "dimgray": "#696969",
            "dimgrey": "#696969",
            "dodgerblue": "#1e90ff",
            "firebrick": "#b22222",
            "floralwhite": "#fffaf0",
            "forestgreen": "#228b22",
            "gainsboro": "#dcdcdc",
            "ghostwhite": "#f8f8ff",
            "gold": "#ffd700",
            "goldenrod": "#daa520",
            "greenyellow": "#adff2f",
            "grey": "#808080",
            "honeydew": "#f0fff0",
            "hotpink": "#ff69b4",
            "indianred": "#cd5c5c",
            "indigo": "#4b0082",
            "ivory": "#fffff0",
            "khaki": "#f0e68c",
            "lavender": "#e6e6fa",
            "lavenderblush": "#fff0f5",
            "lawngreen": "#7cfc00",
            "lemonchiffon": "#fffacd",
            "lightblue": "#add8e6",
            "lightcoral": "#f08080",
            "lightcyan": "#e0ffff",
            "lightgoldenrodyellow": "#fafad2",
            "lightgray": "#d3d3d3",
            "lightgreen": "#90ee90",
            "lightgrey": "#d3d3d3",
            "lightpink": "#ffb6c1",
            "lightsalmon": "#ffa07a",
            "lightseagreen": "#20b2aa",
            "lightskyblue": "#87cefa",
            "lightslategray": "#778899",
            "lightslategrey": "#778899",
            "lightsteelblue": "#b0c4de",
            "lightyellow": "#ffffe0",
            "limegreen": "#32cd32",
            "linen": "#faf0e6",
            "magenta": "#ff00ff",
            "mediumaquamarine": "#66cdaa",
            "mediumblue": "#0000cd",
            "mediumorchid": "#ba55d3",
            "mediumpurple": "#9370db",
            "mediumseagreen": "#3cb371",
            "mediumslateblue": "#7b68ee",
            "mediumspringgreen": "#00fa9a",
            "mediumturquoise": "#48d1cc",
            "mediumvioletred": "#c71585",
            "midnightblue": "#191970",
            "mintcream": "#f5fffa",
            "mistyrose": "#ffe4e1",
            "moccasin": "#ffe4b5",
            "navajowhite": "#ffdead",
            "oldlace": "#fdf5e6",
            "olivedrab": "#6b8e23",
            "orangered": "#ff4500",
            "orchid": "#da70d6",
            "palegoldenrod": "#eee8aa",
            "palegreen": "#98fb98",
            "paleturquoise": "#afeeee",
            "palevioletred": "#db7093",
            "papayawhip": "#ffefd5",
            "peachpuff": "#ffdab9",
            "peru": "#cd853f",
            "pink": "#ffc0cb",
            "plum": "#dda0dd",
            "powderblue": "#b0e0e6",
            "rosybrown": "#bc8f8f",
            "royalblue": "#4169e1",
            "saddlebrown": "#8b4513",
            "salmon": "#fa8072",
            "sandybrown": "#f4a460",
            "seagreen": "#2e8b57",
            "seashell": "#fff5ee",
            "sienna": "#a0522d",
            "skyblue": "#87ceeb",
            "slateblue": "#6a5acd",
            "slategray": "#708090",
            "slategrey": "#708090",
            "snow": "#fffafa",
            "springgreen": "#00ff7f",
            "steelblue": "#4682b4",
            "tan": "#d2b48c",
            "thistle": "#d8bfd8",
            "tomato": "#ff6347",
            "turquoise": "#40e0d0",
            "violet": "#ee82ee",
            "wheat": "#f5deb3",
            "whitesmoke": "#f5f5f5",
            "yellowgreen": "#9acd32",
            "rebeccapurple": "#663399"
        };
    }
};