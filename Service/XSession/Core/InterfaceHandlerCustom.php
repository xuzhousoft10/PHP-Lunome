<?php
/**
 * Namespace defination
 */
namespace X\Service\XSession\Core;

/**
 * Session handler interface.
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
interface InterfaceHandler {
/**
     * Initialize session
     * @link http://www.php.net/manual/en/sessionhandlerinterface.open.php
     * @param save_path string <p>
     * The path where to store/retrieve the session.
     * </p>
     * @param sessionid string <p>
     * The session id.
     * </p>
     * @return void &returns.session.storage.retval;
     */
    public function open ($save_path, $sessionid);

    /**
     * Close the session
     * @link http://www.php.net/manual/en/sessionhandlerinterface.close.php
     * @return void &returns.session.storage.retval;
     */
    public function close ();

    /**
     * Read session data
     * @link http://www.php.net/manual/en/sessionhandlerinterface.read.php
     * @param sessionid string <p>
     * The session id to read data for.
     * </p>
     * @return void the read data.
     */
    public function read ($sessionid);

    /**
     * Write session data
     * @link http://www.php.net/manual/en/sessionhandlerinterface.write.php
     * @param sessionid string <p>
     * The session id.
     * </p>
     * @param sessiondata string <p>
     * The (session_encoded) session data.
     * </p>
     * @return void &returns.session.storage.retval;
     */
    public function write ($sessionid, $sessiondata);

    /**
     * Destroy a session
     * @link http://www.php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param sessionid string <p>
     * The session ID being destroyed.
     * </p>
     * @return void &returns.session.storage.retval;
     */
    public function destroy ($sessionid);

    /**
     * Cleanup old sessions
     * @link http://www.php.net/manual/en/sessionhandlerinterface.gc.php
     * @param maxlifetime string <p>
     * Sessions that have not updated for the last maxlifetime seconds will be removed.
     * </p>
     * @return void &returns.session.storage.retval;
     */
    public function gc ($maxlifetime);
}