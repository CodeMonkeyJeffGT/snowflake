<?php

/**
 * Snowflake 算法
 * 生成 serialId
 */
class Snowflake
{

    /**
     * 进程 id 所占字节
     */
    const PROCESS_ID_BIT = 2;

    /**
     * 进程 id 最大数
     */
    const PROCESS_ID_MAX = 0b11;

    /**
     * 进程 id 移动位数
     */
    const PROCESS_ID_MOVE = 0;

    /**
     * 序号所占字节
     */
    const NUM_BIT = 11;

    /**
     * 序号最大数
     */
    const NUM_MAX = 0b11111111111;

    /**
     * 序号移动位数
     */
    const NUM_MOVE = self::PROCESS_ID_MOVE + self::PROCESS_ID_BIT;

    /**
     * 机器码所占字节
     */
    const MACHINE_ID_BIT = 9;

    /**
     * 机器码最大数
     */
    const MACHINE_ID_MAX = 0b111111111;

    /**
     * 机器码移动位数
     */
    const MACHINE_ID_MOVE = self::NUM_MOVE + self::NUM_BIT;

    /**
     * 时间戳所占字节
     */
    const UNIX_BIT = 41;

    /**
     * 时间戳最大字节
     */
    const UNIX_MAX = 0b11111111111111111111111111111111111111111;

    /**
     * 时间戳移动位数
     */
    const UNIX_MOVE = self::MACHINE_ID_MOVE + self::MACHINE_ID_BIT;

    /**
     * 生成 serialId
     *
     * @return int
     */
    public static function generateId()
    {
        return self::getUnix() << self::UNIX_MOVE
            | self::getMachineId() << self::NUM_MOVE
            | self::getNum() << self::NUM_MOVE
            | self::getProcessId() << self::PROCESS_ID_MOVE;
    }

    /**
     * 获取当前时间戳（41位）
     *
     * @return int
     */
    private static function getUnix()
    {
        $now = microtime(true);
        $now *= 1000;
        $now = $now & self::UNIX_MAX;
        return $now;
    }

    /**
     * 获取机器号（9位 ip）
     *
     * return int
     */
    private static function getMachineId()
    {
        $ip = self::getIp();
        $ipArr = explode(".", $ip);
        $ipNum = $ipArr[0] & ($ipArr[1] << 8) & ($ipArr[2] << 16) & ($ipArr[3] << 24);
        return $ipNum & self::MACHINE_ID_MAX;
    }

    /**
     * 获取序号（永远是0，11位）
     *
     * @return int
     */
    private static function getNum()
    {
        return 0 & self::NUM_MAX;
    }

    /**
     * 获取进程 id（2位）
     */
    private static function getProcessId()
    {
        return posix_getpid() & self::PROCESS_ID_MAX;
    }

    /**
     * 获取当前 ip
     */
    private static function getIp()
    {
        $ip = gethostbyname(gethostname());
        return self::ipCheck($ip) ? $ip : '127.0.0.1';
    }

    /**
     * 判断 ip 格式是否正确
     *
     * @param $ip
     */
    private static function ipCheck($ip)
    {
        $preg = '/(\d{1,3}\.){3}\d{1,3}/';
        return preg_match($preg, $ip);
    }

}