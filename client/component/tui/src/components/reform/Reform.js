/**
 * This file is part of Totara Enterprise Extensions.
 *
 * Copyright (C) 2020 onwards Totara Learning Solutions LTD
 *
 * Totara Enterprise Extensions is provided only to Totara
 * Learning Solutions LTD's customers and partners, pursuant to
 * the terms and conditions of a separate agreement with Totara
 * Learning Solutions LTD or its affiliate.
 *
 * If you do not have an agreement with Totara Learning Solutions
 * LTD, you may not access, use, modify, or distribute this software.
 * Please contact [licensing@totaralearning.com] for more information.
 *
 * @author Simon Chester <simon.chester@totaralearning.com>
 * @module tui
 */

import Vue from 'vue';
import { get, set, structuralDeepClone, result } from 'tui/util';
import { set as vueSet } from 'tui/vue_util';
import { loadLangStrings, isRtl } from 'tui/i18n';
import { getDocumentPosition } from 'tui/dom/position';
import { getTabbableElements } from 'tui/dom/focus';
import BatchingLoadQueue from '../../js/internal/BatchingLoadQueue';
import {
  arrayEqual,
  arrayStartsWith,
  toPath,
  pathFunctionEqual,
  sortEntriesByPath,
  collectErrorValues,
  mergeErrors,
  onlyTouched,
  makeAllTouch,
  collectLangStrings,
} from '../../js/internal/reform/data_structure_utils';
import ValidationResults from '../../js/internal/reform/ValidationResults';

export default {
  provide() {
    return {
      reformScope: {
        getValue: this.get,
        getError: name => get(this.displayedErrors, name),
        getTouched: name => !!get(this.touched, name),
        update: this.update,
        blur: this.blur,
        touch: this.blur,
        register: this.register,
        unregister: this.unregister,
        updateRegistration: this.updateRegistration,
        getInputName: this.getInputName,
        $_internalUpdateSliceState: this.$_internalUpdateSliceState,
      },
    };
  },

  props: {
    /**
     * Initial values for form fields.
     */
    initialValues: {
      type: [Object, Function],
      default: () => ({}),
    },

    /**
     * External errors to display in form.
     */
    errors: Object,

    /**
     * Root-level validator function.
     */
    validate: Function,

    /**
     * Validation mode.
     *
     * 'auto': smart validation
     * 'submit': only validate on submit
     */
    validationMode: {
      type: String,
      default: 'auto',
      validator: x => ['auto', 'submit'].includes(x),
    },
  },

  data() {
    return {
      // structural state
      validators: [],
      registrations: {
        processor: [],
        submitHandler: [],
        element: [],
        changeListener: [],
      },

      // form state
      submitting: false,

      // form content state
      values: structuralDeepClone(result(this.initialValues)),
      touched: {},
      changed: {},

      // generated
      mergedErrors: {},
      validationResults: new ValidationResults({}),
      validatorsErrors: [],
    };
  },

  computed: {
    /**
     * Get displayed errors.
     *
     * @returns {object}
     */
    displayedErrors() {
      return onlyTouched(this.mergedErrors, this.touched);
    },

    /**
     * Work out if we have any errors.
     *
     * @returns {boolean}
     */
    isValid() {
      return collectErrorValues(this.mergedErrors).every(x => !x);
    },
  },

  watch: {
    // revalidate when external errors change
    errors: {
      handler(errors) {
        if (errors) {
          mergeErrors(this.touched, makeAllTouch(errors));
        }
        this.$_validate();
      },
      deep: true,
    },
  },

  created() {
    this.validationQueue = new BatchingLoadQueue({
      handler: this.$_validateInternal,
      wait: 10,
      equal: arrayEqual,
      serial: true,
    });
  },

  methods: {
    /**
     * Update recorded value for input.
     *
     * @param {(string|number|array)} path
     * @param {*} value
     */
    update(path, value) {
      if (path == null) {
        this.values = value;
      } else {
        path = toPath(path);
        vueSet(this.values, path, value);
      }

      this.$_afterChange(path);
    },

    /**
     * Get current value of input.
     *
     * @param {?(string|number|array)} path Path. Omit to return all values.
     * @returns {*}
     */
    get(path) {
      if (path == null) {
        return this.values;
      }
      return get(this.values, path);
    },

    /**
     * Record that input has blurred (been unfocused).
     *
     * @param {(string|number|array)} path
     */
    blur(path) {
      this.touch(path);
    },

    /**
     * Record that input has been touched.
     *
     * @param {(string|number|array)} path
     */
    touch(path) {
      vueSet(this.touched, path, true);
      if (this.validationMode != 'submit') {
        this.$_validate(path);
      }
    },

    /**
     * Register (path, function) of specified type.
     *
     * @param {('validator'|'processor'|'submitHandler'|'changeListener')} type
     * @param {(string|number|array|null)} path
     * @param {function} fn
     */
    register(type, path, fn) {
      switch (type) {
        case 'validator':
          this.$_register(this.validators, path, fn);
          if (this.validationMode != 'submit') {
            this.$_validateIfTouched(path);
          }
          return;
        default:
          if (!this.registrations[type]) {
            this.registrations[type] = [];
          }
          return this.$_register(this.registrations[type], path, fn);
      }
    },

    /**
     * Unregister (path, function) of specified type.
     *
     * @param {('validator'|'processor'|'submitHandler'|'changeListener')} type
     * @param {(string|number|array|null)} path
     * @param {function} fn
     */
    unregister(type, path, fn) {
      switch (type) {
        case 'validator':
          this.$_unregister(this.validators, path, fn);
          if (this.validationMode != 'submit') {
            this.$_validateIfTouched(path);
          }
          return;
        default:
          if (!this.registrations[type]) {
            return;
          }
          return this.$_unregister(this.registrations[type], path, fn);
      }
    },

    /**
     * Helper for updating registration when it changes.
     *
     * Unregisters the old function and registers the new one.
     * Does nothing if they haven't changed.
     *
     * @param {string} type
     * @param {(string|number|array)} path
     * @param {function} fn
     * @param {(string|number|array)} oldPath
     * @param {function} oldFn
     */
    updateRegistration(type, path, fn, oldPath, oldFn) {
      if (
        fn == oldFn &&
        (path == oldPath || arrayEqual(toPath(path), toPath(oldPath)))
      ) {
        // nothing has changed
        return;
      }

      if (oldFn) {
        this.unregister(type, oldPath, oldFn);
      }

      if (fn) {
        this.register(type, path, fn);
      }
    },

    $_register(array, path, fn) {
      const entry = [path, fn];
      if (!array.some(x => pathFunctionEqual(x, entry))) {
        array.push(entry);
      }
    },

    $_unregister(array, path, fn) {
      const entry = [path, fn];
      const index = array.findIndex(x => pathFunctionEqual(x, entry));
      if (index !== -1) {
        array.splice(index, 1);
      }
    },

    /**
     * Get name to use for HTML input.
     *
     * This is mostly for autocomplete support.
     *
     * e.g.:
     * ['name'] => 'name'
     * ['a', 'b', 'c'] => 'a[b][c]'
     *
     * This syntax was chosen as it is the one used by PHP for nested params.
     *
     * @param {(array|string)} path
     * @returns {string}
     */
    getInputName(path) {
      return toPath(path)
        .map((part, i) => (i == 0 ? part : '[' + part + ']'))
        .join('');
    },

    /**
     * Reset form to initial state.
     */
    reset() {
      this.values = structuralDeepClone(result(this.initialValues));
      this.touched = {};
      this.mergedErrors = {};
      this.validatorsErrors = [];
    },

    /**
     * Handle submit event on form.
     *
     * @param {Event} e
     */
    async handleSubmit(e) {
      e.preventDefault();

      return this.submit();
    },

    /**
     * Attempt to submit form, returning form values if valid or null otherwise.
     *
     * @public
     * @returns {Promise<object|null>}
     */
    async trySubmit() {
      this.submitting = true;

      // wait for rerender
      await Vue.nextTick();

      // validate
      await this.$_validate();

      this.submitting = false;
      mergeErrors(this.touched, makeAllTouch(this.mergedErrors));

      // emit
      if (this.isValid) {
        const processors = sortEntriesByPath(
          this.registrations.processor
        ).reverse();
        const submitHandlers = sortEntriesByPath(
          this.registrations.submitHandler
        ).reverse();
        let values = structuralDeepClone(this.values);

        // process values
        for (let i = 0; i < processors.length; i++) {
          const [path, processor] = processors[i];
          if (path === null) {
            // eslint false positive (`values` is not modifiable from outside
            // this function while we are awaiting):
            // eslint-disable-next-line require-atomic-updates
            values = await processor(values);
          } else {
            const result = await processor(get(values, path));
            set(values, path, result);
          }
        }

        // call registered submit handlers
        submitHandlers.forEach(([path, handler]) =>
          handler(path === null ? values : get(values, path))
        );

        return values;
      } else {
        return null;
      }
    },

    /**
     * Trigger submit of form, firing submit event if valid..
     *
     * Returns form values if valid or null otherwise.
     *
     * @public
     * @returns {Promise<object|null>}
     */
    async submit() {
      const values = await this.trySubmit();
      if (values) {
        this.$emit('submit', values);
        return values;
      } else {
        this.focusFirstInvalid();
        return null;
      }
    },

    /**
     * Focus the first invalid field.
     *
     * @public
     * @returns {boolean} success
     */
    focusFirstInvalid() {
      return this.$_focusFirstEl(path => get(this.mergedErrors, path));
    },

    /**
     * Focus the first field.
     *
     * @public
     */
    focus() {
      // find first field (by screen-space position in doucment)
      this.$_focusFirstEl();
    },

    /**
     * Focus first field meeting condition.
     *
     * @internal
     * @param {((path: (string|number|array), getEl: Function) => boolean} [filter]
     * @returns {boolean} success
     */
    $_focusFirstEl(filter) {
      const rtl = isRtl();
      const isLeftBefore = rtl ? (a, b) => a > b : (a, b) => a < b;

      // find first field (by screen-space position in document)
      let firstEl = null;
      let firstPos = null;
      this.registrations.element.forEach(([path, getEl]) => {
        const el = getEl();
        if (el && (!filter || filter(path, getEl))) {
          const pos = getDocumentPosition(el);
          if (
            firstEl == null ||
            pos.top < firstPos.top ||
            (pos.top === firstPos.top && isLeftBefore(pos.left, firstPos.left))
          ) {
            firstEl = el;
            firstPos = pos;
          }
        }
      });

      if (firstEl) {
        const tabbable = getTabbableElements(firstEl);
        if (tabbable.length > 0) {
          tabbable[0].focus();
        } else {
          firstEl.scrollIntoView({ behavior: 'smooth' });
        }
        return true;
      }
      return false;
    },

    /**
     * INTERNAL method for updating a slice of form state.
     *
     * Do not use this method outside of Tui core forms code.
     *
     * @internal
     * @param {(string|number|array)} path
     * @param {({ values, touched }) => { values, touched }} fn Callback. Called with { values, touched }, should return the same shaped object.
     */
    $_internalUpdateSliceState(path, fn) {
      const slice = path
        ? { values: get(this.values, path), touched: get(this.touched, path) }
        : { values: this.values, touched: this.touched };

      const result = fn(slice);

      if (path) {
        vueSet(this.values, path, result.values);
        vueSet(this.touched, path, result.touched);
      } else {
        this.values = result.values;
        this.touched = result.touched;
      }

      this.$_afterChange(toPath(path));
    },

    /**
     * Handle after-change actions.
     *
     * @internal
     * @param {array} path
     */
    $_afterChange(path) {
      this.$emit('change', this.values);

      // run listeners for any parents, self, or children matching
      const listeners = this.registrations.changeListener.filter(
        ([listenerPath]) =>
          listenerPath == null ||
          arrayStartsWith(toPath(listenerPath), path) ||
          arrayStartsWith(path, toPath(listenerPath))
      );

      listeners.forEach(([, handler]) => handler());

      if (this.validationMode != 'submit') {
        this.$_validate(path);
      }
    },

    /**
     * Run validators and update error status.
     *
     * @internal
     * @param {(array|null)} [validatePath]
     * @returns {Promise}
     */
    $_validate(path = null) {
      return this.validationQueue.enqueue(path);
    },

    /**
     * Run validators if path touched.
     *
     * @internal
     * @param {(array|null)} [validatePath]
     */
    $_validateIfTouched(path = null) {
      if (path == null) {
        this.$_validate();
      } else if (get(this.touched, path)) {
        this.$_validate(path);
      }
    },

    /**
     * Run validators for specified paths (called by queue).
     *
     * @internal
     * @param {Array<(string|number|array)>} validatePaths
     */
    async $_validateInternal(validatePaths) {
      validatePaths = validatePaths.map(toPath);
      // figure out what validators to run
      let validators = sortEntriesByPath(this.validators);
      if (this.validate) {
        validators.unshift([null, this.validate]);
      }
      const validateRoot = validatePaths.some(
        x => x.length == 1 && x[0] == null
      );
      let validatorMatcher;
      if (!validateRoot) {
        // run validators for any parents, self, or children matching
        // root validators always run
        const validatorMatcherInner = (path, reqPath) =>
          path == null ||
          arrayStartsWith(toPath(path), reqPath) ||
          arrayStartsWith(reqPath, toPath(path));

        validatorMatcher = path =>
          validatePaths.some(reqPath => validatorMatcherInner(path, reqPath));

        validators = validators.filter(([path]) => validatorMatcher(path));
      }

      // run validators (async)
      let validatorResults = await Promise.all(
        validators.map(([path, validator]) => {
          if (path === null) {
            return Promise.resolve(validator(this.values)).then(x => [path, x]);
          } else {
            const values = get(this.values, path);
            return Promise.resolve(validator(values)).then(validatorResult => {
              let validatorErrors = {};
              vueSet(validatorErrors, path, validatorResult);
              return [path, validatorErrors];
            });
          }
        })
      );

      if (validatorMatcher) {
        // filter out errors from validators matching our path, then replace
        // with new validation results
        validatorResults = sortEntriesByPath(
          this.validatorsErrors
            .filter(([path]) => !validatorMatcher(path))
            .concat(validatorResults)
        );
      }

      // combine errors into a single object
      const mergedErrors = validatorResults.reduce(
        (acc, [, errors]) => mergeErrors(acc, errors),
        this.errors ? structuralDeepClone(this.errors) : {}
      );

      // load strings for errors
      const langStrings = collectLangStrings(mergedErrors);
      if (langStrings.length > 0) {
        await loadLangStrings(langStrings);
      }

      // finally, assign result
      this.validatorsErrors = validatorResults;
      this.mergedErrors = mergedErrors;
      this.validationResults = new ValidationResults(mergedErrors);

      this.$emit('validation-changed', this.validationResults);
    },

    /**
     * Get whether the form is submitting.
     *
     * @returns {boolean}
     */
    getSubmitting() {
      return this.submitting;
    },
  },

  render() {
    // do not return any state directly here, return getter functions instead.
    // otherwise if this component is wrapped, Vue will think the state is being
    // accessed when we pass along all slot props.
    return this.$scopedSlots.default({
      getSubmitting: this.getSubmitting,
      handleSubmit: this.handleSubmit,
      submit: this.submit,
      reset: this.reset,
    });
  },
};
