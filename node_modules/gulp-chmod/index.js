'use strict';
const through = require('through2');
const merge = require('lodash.merge');
const Mode = require('stat-mode');

const defaultMode = 0o777 & (~process.umask());

function normalize(mode) {
	let isCalled = false;
	const newMode = {
		owner: {},
		group: {},
		others: {}
	};

	for (const key of ['read', 'write', 'execute']) {
		if (typeof mode[key] === 'boolean') {
			newMode.owner[key] = mode[key];
			newMode.group[key] = mode[key];
			newMode.others[key] = mode[key];
			isCalled = true;
		}
	}

	return isCalled ? newMode : mode;
}

module.exports = (fileMode, directoryMode) => {
	if (!(fileMode === undefined || typeof fileMode === 'number' || typeof fileMode === 'object')) {
		throw new TypeError('Expected `fileMode` to be undefined/number/object');
	}

	if (directoryMode === true) {
		directoryMode = fileMode;
	}

	if (!(directoryMode === undefined || typeof directoryMode === 'number' || typeof directoryMode === 'object')) {
		throw new TypeError('Expected `directoryMode` to be undefined/true/number/object');
	}

	return through.obj((file, encoding, callback) => {
		let currentMode = fileMode;

		if (file.isNull() && file.stat && file.stat.isDirectory()) {
			currentMode = directoryMode;
		}

		if (currentMode === undefined) {
			callback(null, file);
			return;
		}

		file.stat = file.stat || {};
		file.stat.mode = file.stat.mode || defaultMode;

		if (typeof currentMode === 'object') {
			const statMode = new Mode(file.stat);
			merge(statMode, normalize(currentMode));
			file.stat.mode = statMode.stat.mode;
		} else {
			file.stat.mode = currentMode;
		}

		callback(null, file);
	});
};
