<?php
/**
 * @author Jonathan Moss
 * @copyright 2009 Jonathan Moss <xirisr@gmail.com>
 * @package morph
 */
namespace morph;
/**
 * Morph Enum object
 *
 * @package morph
 */
class Enum
{
    const STATE_NEW   = 'New';
    const STATE_CLEAN = 'Clean';
    const STATE_DIRTY = 'Dirty';

    const DIRECTION_ASC = 1;
    const DIRECTION_DESC = -1;
}